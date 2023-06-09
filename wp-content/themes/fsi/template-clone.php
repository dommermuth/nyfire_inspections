<?php

/*
Template Name: Report Clone
*/

$pid = ( isset( $_GET['pid'] ) ) ? sanitize_text_field( $_GET['pid'] ) : 'new_post';

if( !is_user_logged_in() || !current_user_can( 'edit_posts' ) || !is_numeric($pid)){
	$site_url = get_site_url();
    $url = $site_url . "/wp-login.php";
    wp_redirect($url);
    exit;
}

$post = get_post($pid);

if ($post) {

    /*
	* new post data array
	*/
	$args = array(
		'comment_status' => $post->comment_status,
		'ping_status'    => $post->ping_status,
		'post_author'    => $new_post_author,
		'post_content'   => $post->post_content,
		'post_excerpt'   => $post->post_excerpt,
		'post_name'      => $post->post_name,
		'post_parent'    => $post->post_parent,
		'post_password'  => $post->post_password,
		'post_status'    => 'publish',
		'post_title'     => $post->post_title ." ". date("Y-m-d h:i:sa"),
		'post_type'      => $post->post_type,
		'to_ping'        => $post->to_ping,
		'menu_order'     => $post->menu_order
	);

	/*
		* insert the post by wp_insert_post() function
		*/
	$new_post_id = wp_insert_post( $args );

	/*
		* get all current post terms ad set them to the new post draft
		*/
	$taxonomies = get_object_taxonomies($post->post_type); // returns array of taxonomy names for post type, ex array("category", "post_tag");
	foreach ($taxonomies as $taxonomy) {
		$post_terms = wp_get_object_terms($post_id, $taxonomy, array('fields' => 'slugs'));
		wp_set_object_terms($new_post_id, $post_terms, $taxonomy, false);
	}

	/*
		* duplicate all post meta just in two SQL queries
		*/
	$post_meta_infos = $wpdb->get_results("SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id=$post_id");
	if (count($post_meta_infos)!=0) {
		$sql_query = "INSERT INTO $wpdb->postmeta (post_id, meta_key, meta_value) ";
		foreach ($post_meta_infos as $meta_info) {
			$meta_key = $meta_info->meta_key;
			if( $meta_key == '_wp_old_slug' ) continue;
			$meta_value = addslashes($meta_info->meta_value);
			$sql_query_sel[]= "SELECT $new_post_id, '$meta_key', '$meta_value'";
		}
		$sql_query.= implode(" UNION ALL ", $sql_query_sel);
		$wpdb->query($sql_query);
	}

	//duplicate ACF fields
	$allacfmeta = get_post_meta($pid);
	foreach($allacfmeta as $key => $value){
		if(is_array($value)){
			foreach($value as $val){
				if($val){
					add_post_meta(  $new_post_id, $key, $val, false);
				}
			}
		}
	}

	$site_url = get_site_url();
	$url = $site_url . "/edit?pid=".$new_post_id;
	wp_redirect($url);
}

?>