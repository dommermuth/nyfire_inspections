<?php


class Inspections_Counter_Tables {
	public function __construct() {
		global $wpdb;
		$this->table_name_post_date = $wpdb->prefix . 'inspections_counter_post_date';
		$this->table_name_post_modified = $wpdb->prefix . 'inspections_counter_post_modified';
	}
	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public function remove_database_tables(){
		//return - not sure we want to remove the data accidentally
		global $wpdb;	
		$sql = "DROP TABLE $this->table_name_post_date"; 
		$result = $wpdb->get_results($sql);

		$sql = "DROP TABLE $this->table_name_post_modified"; 
		$result = $wpdb->get_results($sql);

	}
	
	public function add_database_tables(){

		global $wpdb;
		global $inspectionscounter_db_version;
	
		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE $this->table_name_post_date (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			date_created DATETIME DEFAULT CURRENT_TIMESTAMP,
			report_year_month DATETIME NOT NULL,
			site_name varchar(15) NOT NULL,
			site_id int NOT NULL,			
			count mediumint NOT NULL,
			batch int NOT NULL,
			PRIMARY KEY  (id)
		) $charset_collate;";

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		dbDelta( $sql );
		add_option( 'inspectionscounter_db_version', $inspectionscounter_db_version );

		//create seperate table for post_modified
		$sql = "CREATE TABLE $this->table_name_post_modified (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			date_created DATETIME DEFAULT CURRENT_TIMESTAMP,
			report_year_month DATETIME NOT NULL,
			site_name varchar(15) NOT NULL,
			site_id int NOT NULL,			
			count mediumint NOT NULL,
			batch int NOT NULL,
			PRIMARY KEY  (id)
		) $charset_collate;";

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		dbDelta( $sql );
		add_option( 'inspectionscounter_db_version', $inspectionscounter_db_version );
	}

	public function flush_table() {
		$this->remove_database_tables();
		$this->add_database_tables();
	}

	public function insert_row($table, $year_month, $site_name, $site_id, $count, $batch=0){	
		
		global $wpdb;
		$q = "INSERT INTO $table (report_year_month,site_name,site_id,count, batch) VALUES ('$year_month', '$site_name', $site_id, $count, $batch)";
		//$q = "INSERT INTO '".$this->table."' 
				//('site_name', 'site_id', 'year', 'month`, 'count') VALUES ('$site_name', $site_id, $year, $month, $count)
				//ON DUPLICATE KEY UPDATE site_name='".$site_name."', site_id=".$site_id.",year=".$year.", month=".$month; 

		$wpdb->query($q);
	}

	public function auto_insert_row_post_date(){ //store counts for each blog

		if ( function_exists( 'get_sites' ) && class_exists( 'WP_Site_Query' ) ) {
			$batch_number = $this->get_latest_batch_number($this->table_name_post_date);
			if(!$batch_number){
				$batch_number = 0;
			}
			$batch_number++;
			$sites = get_sites();
			foreach ( $sites as $site ) {
				// do something
				if($site->blog_id == 1){
					continue;
				}

				$site_id = $site->blog_id;
				$site_name = strtoupper(str_replace("/","",$site->path));
				switch_to_blog($site->blog_id);
				global $wpdb;
				$post_table = $wpdb->prefix."posts";
				$result_by_month_year = $wpdb->get_results( 
					"SELECT Count(*) AS count, MONTH(r.post_date) as month, YEAR(r.post_date) as year
					FROM ".$post_table." r 
					WHERE post_type='reports' 
					AND post_status='publish'
					GROUP BY EXTRACT(YEAR_MONTH FROM r.post_date)" 
				);
				if ($result_by_month_year){
					
					foreach($result_by_month_year as $result){
						$month = sprintf("%02d", $result->month);
						$year_month_timestamp = strtotime($result->year."-".$month."-01 00:00:00");
						$year_month = date("Y-m-d H:i:s", $year_month_timestamp);
						$this->insert_row($this->table_name_post_date, $year_month, $site_name, $site_id, $result->count, $batch_number);					
					}

				}else{ echo "<br>no res";}
			}
		}
	}

	public function auto_insert_row_post_modified(){ //store counts for each blog

		if ( function_exists( 'get_sites' ) && class_exists( 'WP_Site_Query' ) ) {
			$batch_number = $this->get_latest_batch_number($this->table_name_post_modified);
			if(!$batch_number){
				$batch_number = 0;
			}
			$batch_number++;
			$sites = get_sites();
			foreach ( $sites as $site ) {
				// do something
				if($site->blog_id == 1){
					continue;
				}

				$site_id = $site->blog_id;
				$site_name = strtoupper(str_replace("/","",$site->path));
				switch_to_blog($site->blog_id);
				global $wpdb;
				$post_table = $wpdb->prefix."posts";
				$result_by_month_year = $wpdb->get_results( 
					"SELECT Count(*) AS count, MONTH(r.post_modified) as month, YEAR(r.post_modified) as year
					FROM ".$post_table." r 
					WHERE post_type='reports' 
					AND post_status='publish'
					GROUP BY EXTRACT(YEAR_MONTH FROM r.post_modified)" 
				);
				if ($result_by_month_year){
					
					foreach($result_by_month_year as $result){
						$month = sprintf("%02d", $result->month);
						$year_month_timestamp = strtotime($result->year."-".$month."-01 00:00:00");
						$year_month = date("Y-m-d H:i:s", $year_month_timestamp);
						$this->insert_row($this->table_name_post_modified, $year_month, $site_name, $site_id, $result->count, $batch_number);					
					}

				}else{ echo "<br>no res";}
			}
		}
	}

	public function get_latest_batch_number($table){
		global $wpdb;
		$q_batch = "SELECT id, batch AS most_recent_batch FROM $table ORDER BY id DESC LIMIT 1";
		
		$q_batch_result = $wpdb->get_results($q_batch);
		$batch_number = $q_batch_result[0]->most_recent_batch;
		return $batch_number;
	}

	public function find_batch_differences(){
		global $wpdb;
		$q_diff = "SELECT *, 
					MONTH(r1.report_year_month) as month, 
					YEAR(r1.report_year_month) as year,
					r1.count - r2.count as diff
					FROM $this->table_name_post_date r1, $this->table_name_post_date r2
					WHERE 
						r1.report_year_month = r2.report_year_month AND 
						r1.site_id = r2.site_id AND 
						r1.count != r2.count";
		
		$q_diff_result = $wpdb->get_results($q_diff);
		return $q_diff_result;
	}
}
