<?php

/*
Template Name: CSV
*/
require __DIR__ . '/vendor/autoload.php';
use mikehaertl\wkhtmlto\Pdf;

if( !is_user_logged_in() || !current_user_can( 'edit_posts' )){
	$site_url = get_site_url();
    $url = $site_url . "/wp-login.php";
    wp_redirect($url);
    exit;
}

$pid = ( isset( $_GET['pid'] ) ) ? sanitize_text_field( $_GET['pid'] ) : '';

if (is_numeric($pid)) {

    $report = get_post($pid);

	$post_title = $report->post_title;
    $file_name = "NYFireSafe-FSI-".$post_title;	

	$columns = [
			'Address',
			'Date of Inspection',
			'Door',
			'Door Location',
			'Req. Self-Closing', 
			'Door Self-Closed Yes/No',
			'Type on Self-Closing Device',
			'Deficiencies Found',
			'FDNY Sign Required  Yes/No',
			'FDNY Sign Present Yes/No',
			'Missing Required Sign',
			'Image URL',
			'Image Caption'
			];	


	$street_address = get_field("address", $pid);
    $city = get_field("city", $pid); 
	$state = get_field("state", $pid);
	$zip_code = get_field("zip_code", $pid); 
	$date_of_inspection = get_field("date_of_inspection", $pid); 
	$date_of_inspection_formatted = date("m-d-Y", strtotime($date_of_inspection));

	if( have_rows("doors", $pid) ):

		header('Content-type: application/csv; charset=UTF-8');
		header('Content-Disposition: attachment; filename='.$file_name.'.csv');
		$file = fopen('php://output', 'w');
		fputcsv($file, $columns);
		while( have_rows("doors", $pid) ) : the_row();

			$door_type = get_sub_field('type');
			$door_location = get_sub_field('door_location');
			$stairway_letter = get_sub_field('stairway_letter');
			$stairway_floor = get_sub_field('stairway_floor');

			if($door_type == "Stairway Door"){
				$door_location = $stairway_letter.$stairway_floor;
			}			

			$self_closing_required = get_sub_field('self_closing_required');
			$door_closer_type = get_sub_field('door_closer_type');
			$door_closer_other = get_sub_field('door_closer_other');
			if($door_closer_type == "Other"){
				$door_closer_type = $door_closer_other;
			}
			$does_self_closing_door_operate_properly = get_sub_field('does_self_closing_door_operate_properly');
			$deficiencies_found = get_sub_field('deficiencies_found');
			$deficiencies_other = get_sub_field('deficiencies_other');
			if($deficiencies_found == "Other"){
				$deficiencies_found = str_replace("’","'",$deficiencies_other);
				//$image_caption = str_replace("’","'",$image_caption);
			}

			if(empty($deficiencies_found)){
				$deficiencies_found = "none";
			}

			$is_fdny_sign_required = get_sub_field('is_fdny_sign_required');
			$is_fdny_sign_present = get_sub_field('is_fdny_sign_present');
			$what_is_the_required_sign = get_sub_field('what_is_the_required_sign');
			$image = get_sub_field('image');
			$image_url = $image["url"];
			$image_caption = get_sub_field('image_caption');
			$image_caption = str_replace("’","'",$image_caption);

			if($is_fdny_sign_required == "no"){
				$is_fdny_sign_present = "n/a";
				$what_is_the_required_sign = [];
				$what_is_the_required_sign[] = "n/a";
			}


			if(empty($image_caption)){
				$image_caption = "none given";
			}

			if(empty($image_url)){
				$image_url = "no picture taken";
			}

			fputcsv($file,	[
								$street_address, 
								$date_of_inspection_formatted,
								$door_type,
								$door_location,
								$self_closing_required,
								$does_self_closing_door_operate_properly,
								$door_closer_type,
								$deficiencies_found,
								$is_fdny_sign_required,
								$is_fdny_sign_present,
								implode(", ",$what_is_the_required_sign),
								$image_url,
								$image_caption
							]
					); 
		endwhile;
		
		fclose($file);

	endif;
	exit();	

}else{

    $site_url = get_site_url();
    $url = $site_url . "/";
    wp_redirect($url);
    exit;
}

function buildCSV($file_name, $headers,$columns, $pid){

	
}

?>