<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/wp-config.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/wp-load.php');

//to test if user is logged in
function ajax_check_user_logged_in() {
    echo is_user_logged_in()?1:0;
    die();
}
add_action('wp_ajax_is_user_logged_in', 'ajax_check_user_logged_in');
add_action('wp_ajax_nopriv_is_user_logged_in', 'ajax_check_user_logged_in');

function get_building() {
	$user = wp_get_current_user();
	$user_id = $user->ID;
	$search_text = $_POST['search_text'];
	if (isset($search_text) && $user_id) {
		$buildings_ar = [];
		//get results from local DB
		global $wpdb;
		$result = $wpdb->get_results( "SELECT * FROM wp_posts WHERE post_type='reports' AND post_status='publish' AND post_title LIKE '%$search_text%'" );
		if ($result){
			foreach($result as $report){
				$building = [
					"src" => "local",
					"id" => $report->ID,
					"title" => $report->post_title,
					"date" => $report->post_date
				];

				$buildings_ar[] = $building;
			}
		}

		//get results from CRM
		$url =  'https://crm.nyfiresafe.com/api/buildings';
		$api_token = 'wbdTzecoXpW8dxrbOdOHrWbGJaerjAGHlkD601CHKMHUzzoHRrr1CQheBX4z';

		$header = array(
				"Accept: application/json",
				"Api-Token: " . $api_token,
				);
		$c_url = $url . "?term=" . urlencode($search_text);

		//initialize curl
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_ENCODING, "gzip");
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
		curl_setopt($ch, CURLOPT_URL, $c_url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

		//get a raw response for debugging
		$raw_response = curl_exec($ch);
		$response = json_decode($raw_response);

		foreach($response->buildings as $item){
			//var_dump($item);
			$building = [
					"src" => "crm",
					"id" => $item->id,
					"title" => $item->address,
					"zip" => $item->zip,
					"state" => $item->state,
					"city" => $item->city,
				];
			$buildings_ar[] = $building;
		}

		
		$table = "<table id='buildings'><tr><th>Address</th><th>Date</th><th>Source</th><th>New</th><th>Clone</th><th>Edit</th><th>View</th><th>CSV</th><th>Del.</th></tr>";
		foreach($buildings_ar as $building){
			$nonce_url = get_delete_post_link( $building['id'] );
			$tr = "";
			if($building['src'] == "crm"){
				$tr .= "<tr><td>" . $building['title'] . "</td><td>&nbsp;</td><td>CRM</td><td>
						<a href='/new?address=".$building['title']."&state=".$building['state']."&city=".$building['city']."&zip=".$building['zip']."' class='btn new' data-id='".$building['id']."' data-src='".$building['src']."'>New</a>
						</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>";
			}else{
				$date=date_create($building['date']);
				$tr .= "<tr><td>" . $building['title'] . "</td><td>" . date_format($date,"Y/m/d H:i:s") . "</td><td><span class='red bold'>FSI</span></td>
						<td><a href='/new?address=".$building['title']."&state=".$building['state']."&city=".$building['city']."&zip=".$building['zip']."' class='btn new' data-id='".$building['id']."' data-src='".$building['src']."'>New</a></td>
						<td><a href='/clone?pid=".$building['id']."' class='btn clone' data-id='".$building['id']."' data-src='".$building['src']."'>Clone</a></td>
						<td><a href='/edit?pid=".$building['id']."' class='btn edit' data-id='".$building['id']."' data-src='".$building['src']."'>Edit</a></td>
						<td><a href='/report/pdf?pid=".$building['id']."' class='btn view' data-id='".$building['id']."' data-src='".$building['src']."' target='_blank'>PDF</a></td>
						<td><a href='/csv/?pid=".$building['id']."' class='btn view' data-id='".$building['id']."' data-src='".$building['src']."'>CSV</a></td>						
						<td><a onclick='return confirm_click();' href='".$nonce_url."'  data-id='".$building['id']."' data-src='".$building['src']."'><i class='fa fa-trash' ></i></a></td></tr>";
			}
			$table .= $tr;
		}
		$table .= "</table>";

		//add js for confirmation on delete
		$table .= "<script>function confirm_click(){ return confirm('Are you sure you want to delete this report?'); }</script>";

		echo $table;
	}
	echo false;
	wp_die();
}
add_action( 'wp_ajax_nopriv_get_building',  'get_building' );
add_action( 'wp_ajax_get_building','get_building' );