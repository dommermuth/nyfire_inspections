<?php
use CommonMark\Node\Text;

//This action gives the means to pass post id in edit url
function my_acf_submit_form(  $form, $post_id ) {
    wp_redirect(  home_url().'/edit?pid='.$post_id );
    exit;
}
add_action('acf/submit_form', 'my_acf_submit_form', 10, 2);

//allows for AJAX saving of report editing
add_action( 'wp_ajax_save_my_data', 'acf_form_head' );
add_action( 'wp_ajax_nopriv_save_my_data', 'acf_form_head' );

//adds new custom option to ACF - not used for anything yet
function progress_check_render_field_settings( $field ) {

	acf_render_field_setting( $field, array(
		'label'			=> __('Include in progress assesment?'),
		'instructions'	=> '',
		'name'			=> 'progresscheck',
		'type'			=> 'true_false',
		'ui'			=> 1,
	), true);

}
//add_action('acf/render_field_settings', 'progress_check_render_field_settings');

//to be used in conjuction with the progress check
function progress_check_prepare_field( $field ) {

	// bail early if no 'admin_only' setting
	if( !empty($field['progresscheck']) ) {
		//var_dump($field);exit;
		$fieldname = $field["name"];
		//add hidden input field to all fields we want to progress check
		echo '<input type="hidden" class="progress-check" data-fieldname="'.$fieldname.'" />';
	}

	return $field;
}
//add_filter('acf/render_field', 'progress_check_prepare_field');

//Populate ACF select field 'image_field'
function acf_load_report_field_choices_image_field($field) {
    // reset choices
    $field['choices'] = array();
	$field['choices'][ 0 ] = "Select";
	if( have_rows("form_groups","options") ):

		while( have_rows("form_groups","options") ) :
			the_row();
			$button_label = get_sub_field('button_label');
			$field_id = get_sub_field('field_id');
			$post_id = get_sub_field('post_id');

			$fields = acf_get_fields( $post_id );
			foreach ( $fields as $f ) {
				$field_name = $f['name'];
				if(strpos($field_name, "image") !== false){
					//echo $field_name . '<br>';
					$field['choices'][ $field_name ] = $field_name;
				}
			}

		endwhile;
		// No value.
	else :
		// Do something...
		echo "No Form Groups found";
	endif;

	//var_dump($field);

    return $field;
}
add_filter('acf/load_field/name=image_field', 'acf_load_report_field_choices_image_field');

//add_filter('acf/render_field', 'progress_check_prepare_field');

//Populate ACF select field 'image_field'
function acf_load_report_field_choices_image_field_2($field) {
    // reset choices
    $field['choices'] = array();
	$field['choices'][ 0 ] = "Select";
	if( have_rows("form_groups","options") ):

		while( have_rows("form_groups","options") ) :
			the_row();
			$button_label = get_sub_field('button_label');
			$field_id = get_sub_field('field_id');
			$post_id = get_sub_field('post_id');

			$fields = acf_get_fields( $post_id );
			foreach ( $fields as $f ) {
				$field_name = $f['name'];
				if(strpos($field_name, "image") !== false){
					//echo $field_name . '<br>';
					$field['choices'][ $field_name ] = $field_name;
				}
			}

		endwhile;
		// No value.
	else :
		// Do something...
		echo "No Form Groups found";
	endif;

    return $field;
}
add_filter('acf/load_field/name=image_field_2', 'acf_load_report_field_choices_image_field_2');

//Populate ACF select field 'search_and_replace'
function acf_load_report_field_choices_all($field) {
    // reset choices
    $field['choices'] = array();
	$field['choices'][ 0 ] = "Select";
	if( have_rows("form_groups","options") ):

		while( have_rows("form_groups","options") ) :
			the_row();
			$button_label = get_sub_field('button_label');
			$field_id = get_sub_field('field_id');
			$post_id = get_sub_field('post_id');

			$fields = acf_get_fields( $post_id );
			foreach ( $fields as $f ) {
				$field_name = $f['name'];
				$field['choices'][ $field_name ] = $field_name;
			}

		endwhile;
		// No value.
	
	else :
		// Do something...
		echo "No Form Groups found";
	endif;

    return $field;
}
add_filter('acf/load_field/name=fields', 'acf_load_report_field_choices_all');

//Populate ACF select field 'fields' for restaurants and hotels'
function acf_load_report_field_choices_rest_hotel_images($field) {
	    // reset choices
    $field['choices'] = array();
	$field['choices'][ 0 ] = "Select";
	if( have_rows("form_groups","options") ):
		//eating_establishments
		while( have_rows("form_groups","options") ) :
			the_row();
			
			$post_id = get_sub_field('post_id');
			$fields = acf_get_fields( $post_id );

			foreach ( $fields as $f ) {
				$field_name = $f['name'];
				
				if($field_name == "eating_establishments" || $field_name == "hotels"){

					$field_keys = [["eating_establishments", "field_620425e9be097"],["hotels","field_62043b6e72da1" ]];
					$key =  array_column($field_keys, $field_name);
					$fields2 = get_field_object("field_620425e9be097",  $post_id); 
					//var_dump($fields2);

					foreach ($fields2["sub_fields"] as $subfields){
							$nme = $subfields["name"];
							if (strpos($nme, "images") !== false){
							   $field['choices'][ $nme ] = $nme;
							}
					}
				}
			}

		endwhile;
		// No value.
	else :
		// Do something...
		echo "No Form Groups found";
	endif;

    return $field;
}
add_filter('acf/load_field/name=fields_restaurant_hotel_images', 'acf_load_report_field_choices_rest_hotel_images');

//Populate ACF select field 'fields' for restaurants and hotels'
function acf_load_report_field_choices_retail_images($field) {
	    // reset choices
    $field['choices'] = array();
	$field['choices'][ 0 ] = "Select";
	if( have_rows("form_groups","options") ):
		//eating_establishments
		while( have_rows("form_groups","options") ) :
			the_row();
			
			$post_id = get_sub_field('post_id');
			$fields = acf_get_fields( $post_id );

			foreach ( $fields as $f ) {
				$field_name = $f['name'];
				
				if($field_name == "retail_establishments"){

					$field_keys = [["retail_establishments", "field_6217c3ae2fc3d" ]];
					$key =  array_column($field_keys, $field_name);
					$fields2 = get_field_object("field_6217c3ae2fc3d",  $post_id); 
					//var_dump($fields2);

					foreach ($fields2["sub_fields"] as $subfields){
							$nme = $subfields["name"];
							if (strpos($nme, "images") !== false){
							   $field['choices'][ $nme ] = $nme;
							}
					}
				}
			}

		endwhile;
		// No value.
	else :
		// Do something...
		echo "No Form Groups found";
	endif;

    return $field;
}
add_filter('acf/load_field/name=fields_retail_images', 'acf_load_report_field_choices_retail_images');


//Populate ACF select field 'fields' for restaurants and hotels'
function acf_load_report_field_choices_doors($field) {
	    // reset choices
    $field['choices'] = array();
	$field['choices'][ 0 ] = "Select";
	if( have_rows("form_groups","options") ):
		//eating_establishments
		while( have_rows("form_groups","options") ) :
			the_row();
			
			$post_id = get_sub_field('post_id');
			$fields = acf_get_fields( $post_id );

			foreach ( $fields as $f ) {
				$field_name = $f['name'];
				
				if($field_name == "doors"){

					$field_keys = [["doors", "field_624db857cd756"]];
					$key =  array_column($field_keys, $field_name);
					$fields2 = get_field_object("field_624db857cd756",  $post_id);

					foreach ($fields2["sub_fields"] as $subfields){
							$nme = $subfields["name"];
							$field['choices'][ $nme ] = $nme;
					}
				}
			}

		endwhile;
		// No value.
	else :
		// Do something...
		echo "No Form Groups found";
	endif;

    return $field;
}
add_filter('acf/load_field/name=fields_doors', 'acf_load_report_field_choices_doors');

//Populate ACF select field 'fields' for risers'
function acf_load_report_field_choices_standpipe_risers($field) {
	    // reset choices
    $field['choices'] = array();
	$field['choices'][ 0 ] = "Select";
	if( have_rows("form_groups","options") ):
		
		while( have_rows("form_groups","options") ) :
			the_row();
			
			$post_id = get_sub_field('post_id');
			$fields = acf_get_fields( $post_id );

			foreach ( $fields as $f ) {
				$field_name = $f['name'];
				
				if($field_name == "standpipe_risers"){

					$field_keys = [["standpipe_risers", "field_63866ed8a7be0"]];
					$key =  array_column($field_keys, $field_name);
					$fields2 = get_field_object("field_63866ed8a7be0",  $post_id);

					foreach ($fields2["sub_fields"] as $subfields){
							$nme = $subfields["name"];
							$field['choices'][ $nme ] = $nme;
					}
				}
			}

		endwhile;
		// No value.
	else :
		// Do something...
		echo "No Form Groups found";
	endif;

    return $field;
}
add_filter('acf/load_field/name=fields_standpipe_risers', 'acf_load_report_field_choices_standpipe_risers');

//Populate ACF select field 'fields' for risers'
function acf_load_report_field_choices_sprinkler_risers($field) {
	    // reset choices
    $field['choices'] = array();
	$field['choices'][ 0 ] = "Select Riser Attribute";
	if( have_rows("form_groups","options") ):
		
		while( have_rows("form_groups","options") ) :
			the_row();
			
			$post_id = get_sub_field('post_id');
			$fields = acf_get_fields( $post_id );

			foreach ( $fields as $f ) {
				$field_name = $f['name'];
				
				if($field_name == "sprinkler_risers"){

					$field_keys = [["sprinkler_risers", "field_632dbcd1302b8"]];
					$key =  array_column($field_keys, $field_name);
					$fields2 = get_field_object("field_632dbcd1302b8",  $post_id);

					foreach ($fields2["sub_fields"] as $subfields){
							$nme = $subfields["name"];
							$field['choices'][ $nme ] = $nme;
					}
				}
			}

		endwhile;
		// No value.
	else :
		// Do something...
		echo "No Form Groups found";
	endif;

    return $field;
}
add_filter('acf/load_field/name=fields_sprinkler_risers', 'acf_load_report_field_choices_sprinkler_risers');

//Populate ACF select field 'fields' for fire pumps'
function acf_load_report_field_choices_fire_pumps($field) {
	    // reset choices
    $field['choices'] = array();
	$field['choices'][ 0 ] = "Select Fire Pump Attribute";
	if( have_rows("form_groups","options") ):
		
		while( have_rows("form_groups","options") ) :
			the_row();
			
			$post_id = get_sub_field('post_id');
			$fields = acf_get_fields( $post_id );

			foreach ( $fields as $f ) {
				$field_name = $f['name'];
				
				if($field_name == "fire_pumps"){

					$field_keys = [["fire_pumps", "field_649490d911bcb"]];
					$key =  array_column($field_keys, $field_name);
					$fields2 = get_field_object("field_649490d911bcb",  $post_id);

					foreach ($fields2["sub_fields"] as $subfields){
							$nme = $subfields["name"];
							$field['choices'][ $nme ] = $nme;
					}
				}
			}

		endwhile;
		// No value.
	else :
		// Do something...
		echo "No Form Groups found";
	endif;

    return $field;
}
add_filter('acf/load_field/name=fields_fire_pumps', 'acf_load_report_field_choices_fire_pumps');

//Populate ACF select field 'fields' for restaurants and hotels'
function acf_load_report_field_choices_rest_hotel($field) {
	    // reset choices
    $field['choices'] = array();
	$field['choices'][ 0 ] = "Select";
	if( have_rows("form_groups","options") ):
		//eating_establishments
		while( have_rows("form_groups","options") ) :
			the_row();
			
			$post_id = get_sub_field('post_id');
			$fields = acf_get_fields( $post_id );

			foreach ( $fields as $f ) {
				$field_name = $f['name'];
				
				if($field_name == "eating_establishments" || $field_name == "hotels"){

					$field_keys = [["eating_establishments", "field_620425e9be097"],["hotels","field_62043b6e72da1" ]];
					$key =  array_column($field_keys, $field_name);
					$fields2 = get_field_object("field_620425e9be097",  $post_id); 
					//var_dump($fields2);

					foreach ($fields2["sub_fields"] as $subfields){
							$nme = $subfields["name"];
							$field['choices'][ $nme ] = $nme;

					}
				}
			}

		endwhile;
		// No value.
	else :
		// Do something...
		echo "No Form Groups found";
	endif;

    return $field;
}
add_filter('acf/load_field/name=fields_restaurant_hotel', 'acf_load_report_field_choices_rest_hotel');

//Populate ACF select field 'fields' for restaurants and hotels'
function acf_load_report_field_choices_retail($field) {
	    // reset choices
    $field['choices'] = array();
	$field['choices'][ 0 ] = "Select";
	if( have_rows("form_groups","options") ):
		//eating_establishments
		while( have_rows("form_groups","options") ) :
			the_row();
			
			$post_id = get_sub_field('post_id');
			$fields = acf_get_fields( $post_id );

			foreach ( $fields as $f ) {
				$field_name = $f['name'];
				
				if($field_name == "retail_establishments"){

					$field_keys = [["retail_establishments", "field_6217c3ae2fc3d"]];
					$key =  array_column($field_keys, $field_name);
					$fields2 = get_field_object("field_6217c3ae2fc3d",  $post_id); 
					//var_dump($fields2);

					foreach ($fields2["sub_fields"] as $subfields){
							$nme = $subfields["name"];
							$field['choices'][ $nme ] = $nme;

					}
				}
			}

		endwhile;
		// No value.
	else :
		// Do something...
		echo "No Form Groups found";
	endif;

    return $field;
}
add_filter('acf/load_field/name=fields_retail', 'acf_load_report_field_choices_retail');

//Populate ACF select field 'search_and_replace'
function acf_load_report_field_choices($field) {
    // reset choices
    $field['choices'] = array();
    $group_id = 1392; //Reports - Building Info
    $fields = acf_get_fields( $group_id );

    foreach ( $fields as $f ) {
        $field_name = $f['name'];
        $field['choices'][ $field_name ] = $field_name;
    }
    return $field;
}
add_filter('acf/load_field/name=search_and_replace', 'acf_load_report_field_choices');


//ACF - Adds Theme Support to Admin
if( function_exists('acf_add_options_page') ) {

    acf_add_options_page(array(
		'page_title' 	=> 'Theme General Settings',
		'menu_title'	=> 'Theme Settings',
		'menu_slug' 	=> 'theme-general-settings',
		'capability'	=> 'edit_posts',
		'redirect'		=> false
	));
}
