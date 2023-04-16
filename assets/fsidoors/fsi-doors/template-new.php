<?php

/*
Template Name: Report New
*/

//populate fields if any in URL string
$title = ( isset( $_GET['address'] ) ) ? sanitize_text_field( $_GET['address'] ) : '';
$state = ( isset( $_GET['state'] ) ) ? sanitize_text_field( $_GET['state'] ) : '';
$city = ( isset( $_GET['city'] ) ) ? sanitize_text_field( $_GET['city'] ) : '';
$zip = ( isset( $_GET['zip'] ) ) ? sanitize_text_field( $_GET['zip'] ) : '';

if( !is_user_logged_in() || !current_user_can( 'edit_posts' ) || !$title){
	$site_url = get_site_url();
    $url = $site_url . "/wp-login.php";
    wp_redirect($url);
    exit;
}

$date = date("Y-m-d h:i:sa");

$args = array(
	'post_status'    => 'publish',
	'post_title'     => $title ." [". $date ."]",
	'post_type'      => "reports"
);

/*
* insert the post by wp_insert_post() function
*/
$new_post_id = wp_insert_post( $args );	

if(!empty($state)){
	update_field( 'field_61aa51e81175f', $state, $new_post_id  ); //state
}

if(!empty($title)){
	update_field( 'field_61aa51af1175d', $title, $new_post_id ); //state
}



if(!empty($city)){
	update_field( 'field_61aa51da1175e', $city, $new_post_id  ); //city
}

if(!empty($zip)){
	update_field( 'field_61aa522e11760', $zip, $new_post_id  ); //zip
}

$site_url = get_site_url();
$url = $site_url . "/edit?pid=".$new_post_id;
wp_redirect($url);

?>