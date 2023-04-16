<?php
/*
Plugin Name: Inspections Counter
Description: Count inspections by month, by inspection date or by report creation date
Version:     1.0.0
Author:      Kurt Dommermuth
Author URI:  http://dommermuth.com
License:     GPL2
*/


if ( ! defined( 'ABSPATH' ) ) {
    exit( 'restricted access' );
}

/**
* The class responsible for adding and remove tables
*/
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'inspections-counter/includes/inspections-count-tables.php';

//deal with tables
/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-plugin-name-activator.php
 */
function activate_Inspections_Counter() {
	$tables = new Inspections_Counter_Tables();
	$tables->add_database_tables();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-plugin-name-deactivator.php
 */
function deactivate_Inspections_Counter() {
	$tables = new Inspections_Counter_Tables();
	$tables->remove_database_tables();
}

register_activation_hook( __FILE__, 'activate_Inspections_Counter' );
register_deactivation_hook( __FILE__, 'deactivate_Inspections_Counter' );

//set up cron - Save repports to database daily
if ( ! wp_next_scheduled( 'wpdocs_task_hook' ) ) {
	wp_schedule_event( time(), 'daily', 'wpdocs_task_hook' );
}
add_action( 'wpdocs_task_hook', 'wpdocs_task_function' ); // 'wpdocs_task_hook` is registered when the event is scheduled

/**
 * Save repports to database
 */
function wpdocs_task_function() {
	$tables = new Inspections_Counter_Tables();
	$tables->auto_insert_row_post_date();
	$tables->auto_insert_row_post_modified();
}

if ( ! function_exists( 'inspections_admin_include_css_and_js' ) ) {
    add_action( 'admin_enqueue_scripts', 'inspections_admin_include_css_and_js' );
    function inspections_admin_include_css_and_js() {        
        /* admin script */
		$user = wp_get_current_user();
		if($user->ID != 1){
			return;
		}
		wp_enqueue_style( 'inspections-css', plugin_dir_url( __FILE__ ) . '/assets/css/inspections-style.css', [], '', 'all' );
        wp_register_script( 'inspections-script', plugin_dir_url( __FILE__ ) . 'assets/js/inspections-script.js', array( 'jquery' ) );
        wp_enqueue_script( 'inspections-script' );
    }
}


if ( ! function_exists( 'inspections_add_network_admin_menu' ) ) {
    add_action( 'network_admin_menu', 'inspections_add_network_admin_menu' );
    function inspections_add_network_admin_menu() {  
		$user = wp_get_current_user();
		if($user->ID != 1){
			return;
		}
        add_menu_page( 'WordPress Multisite Inspections Count', 'Inspections Count', 'manage_options', 'wordpress-multisite-inspections-count', 'inspections_settings', 'dashicons-update' );      
    }
}

if ( ! function_exists( 'inspections_settings' ) ) {
    function inspections_settings() {

        $tables = new Inspections_Counter_Tables();
		$user = wp_get_current_user();
		//only show to me
		if($user->ID != 1){
			return;
		}		
		$batch_number_post_date = $tables->get_latest_batch_number($tables->table_name_post_date);

		//search for differences between batches
		$batch_diff = $tables->find_batch_differences();
		if($batch_diff){
			echo "<h2>Difference found</h2>";
			echo "<table class='inspections-count-table'>";
			echo "<tr><th>Site ID</th><th>Year</th><th>Month</th><th>Count</th><th>Batch Number</th><th>Difference</th>";
			$totals_count = 0;
			foreach($batch_diff as $result){
				$totals_count =$totals_count+$result->count;
				echo "<tr><td>".$result->site_id."</td><td>".$result->year."</td><td>".$result->month."</td><td>". $result->count."</td><td>". $result->batch."</td><td>". $result->diff."</td></tr>";
			}
			echo "</table>";
			echo "<hr>";
		}

		//create a dropdown of batch links
		if($batch_number_post_date > 0){
			echo '<div class="batch-selector-container">';
			echo '<select id="batch-selector">';
			echo '<option value="">Select Batch Number</option>';
			for($batch_count = $batch_number_post_date; $batch_count > 0; $batch_count--){
				echo '<option value="'.$batch_count.'">'.$batch_count.'</option>';
			}
			echo '</select>';
			echo '</div>';
			echo '<script>
					const selectElement = document.getElementById("batch-selector");
					selectElement.addEventListener("change", () => {
					  const selectedOptionValue = selectElement.value;
					  const curUrl = window.location.href;
					  window.location.replace(curUrl + "&batch-number="+selectedOptionValue);
					});
				</script>';
		}else{
			//create a first batch
			$tables->auto_insert_row_post_date();
			$tables->auto_insert_row_post_modified();
		}
		//see if a batch number is query string
		// If not request set
		if ( ! isset( $_REQUEST[ "batch-number" ] ) || empty( $_REQUEST[ "batch-number" ] ) ) {
			$batch_number_post_date = $tables->get_latest_batch_number($tables->table_name_post_date);
		}else{ 
			// Set so process it
			$batch_number_post_date =  strip_tags( (string) wp_unslash( $_REQUEST[ "batch-number" ] ) );
		}

        global $wpdb;		
		//loop through sites search gathering counts based on post_date (not post_modified)
		if ( function_exists( 'get_sites' ) && class_exists( 'WP_Site_Query' ) ) {
			$sites = get_sites();
			
			foreach ( $sites as $site ) {
				// do something
				if($site->blog_id == 1){
					continue;
				}

				$site_id = $site->blog_id;
				$site_name = $site->path;
				$site_name = strtoupper(str_replace("/","",$site->path));
				switch_to_blog($site_id);
				global $wpdb;
				$post_table = $wpdb->prefix."posts";

				echo '<div class="report-container">';								
				
				$q = "SELECT *, MONTH(`report_year_month`) AS month, YEAR(`report_year_month`) AS year FROM $tables->table_name_post_date WHERE site_id=$site_id AND batch=$batch_number_post_date ORDER BY report_year_month DESC";
				$monthly_by_creation_date = $wpdb->get_results($q);				
				if ($monthly_by_creation_date){
					echo '<div class="site">';
					echo "<h2>".$site_name." by post_date</h2>";
					echo "<table class='inspections-count-table'>";
					echo "<tr><th>Site ID</th><th>Year</th><th>Month</th><th>Count</th><th>Batch Number</th>";
					$totals_count = 0;
					foreach($monthly_by_creation_date as $result){
						$totals_count =$totals_count+$result->count;
						$dateObj   = DateTime::createFromFormat('!m', $result->month);
						$monthName = $dateObj->format('F');
						echo "<tr><td>".$site_id."</td><td>".$result->year."</td><td>".$monthName."</td><td>". $result->count."</td><td>". $result->batch."</td></tr>";
					}
					echo "</table>";
					echo "<p class='total-count'>$site_name totals: ". $totals_count."</p>";
					echo '</div>'; //site
				}else{ echo "<br>no results for post date";}

				$q = "SELECT *, MONTH(`report_year_month`) AS month, YEAR(`report_year_month`) AS year FROM $tables->table_name_post_modified WHERE site_id=$site_id AND batch=$batch_number_post_date ORDER BY report_year_month DESC";
				$monthly_by_creation_date = $wpdb->get_results($q);				
				if ($monthly_by_creation_date){
					echo '<div class="site">';
					echo "<h2>".$site_name." by post_modified</h2>";
					echo "<table class='inspections-count-table'>";
					echo "<tr><th>Site ID</th><th>Year</th><th>Month</th><th>Count</th><th>Batch Number</th>";
					$totals_count = 0;
					foreach($monthly_by_creation_date as $result){
						$totals_count =$totals_count+$result->count;
						$dateObj   = DateTime::createFromFormat('!m', $result->month);
						$monthName = $dateObj->format('F');
						echo "<tr><td>".$site_id."</td><td>".$result->year."</td><td>".$monthName."</td><td>". $result->count."</td><td>". $result->batch."</td></tr>";
					}
					echo "</table>";
					echo "<p class='total-count'>$site_name totals: ". $totals_count."</p>";
					echo '</div>'; //site
				}else{ echo "<br>no results for post modified";}


				echo '</div>'; //report-container
				echo '<hr>';
			}
			
		}

		return;

		?>
            <div class="wrap">
                <h2>Report</h2>
                <hr>
                <form method="post">
                <table class="form-table">
                    <tbody> 
                        <tr>
                            <th scope="row">Reports Count</th>
                            <td>

                            </td>
                        </tr>
                    </tbody>
                </table>
                <script type="text/javascript">
                    jQuery( document ).ready( function( $ ) {

                    });
                </script>                
                <p class="submit"><input name="inspections_submit" class="button button-primary" value="Update" type="submit"></p>
                </form>
            </div>
        <?php
    }
}