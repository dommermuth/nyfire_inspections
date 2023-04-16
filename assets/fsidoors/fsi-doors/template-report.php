<?php

/*
Template Name: Report
*/


$pid = ( isset( $_GET['pid'] ) ) ? sanitize_text_field( $_GET['pid'] ) : 'new_post';


if (is_numeric($pid)) {

	echo '<style>
			 .elementor-location-header {
				display:none;
			}
		</style>';

	acf_form_head();
	get_header("report");
    //$post = get_post($pid);

	echo "<div id='pdf'>";

	//get page options to loop through
	$linked_pages_ar = get_field("report_pages","options");

	foreach($linked_pages_ar as $linked_page_id) {

		// Check value exists.
		if( have_rows('flexible_content',$linked_page_id) ):

			// Loop through rows.
			while ( have_rows('flexible_content',$linked_page_id) ) :
				the_row();

				// Case: Paragraph layout.
				if( get_row_layout() == 'html' ):

					include("parts/flex_html.php");

				elseif( get_row_layout() == 'image_block' ):

					include("parts/flex_image_block.php");

				elseif( get_row_layout() == 'form_results' ):

					include("parts/flex_form_results.php");

				endif;

				// End loop.
			endwhile;

			// No value.
		else :
			// Do something...
		endif;

	} //end linked_pages_ar foreach

	echo "</div>";



}else{

    $site_url = get_site_url();
    $url = $site_url . "/";
    wp_redirect($url);
    exit;
}

?>