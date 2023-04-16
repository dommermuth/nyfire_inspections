<?php

/*
Template Name: Report Generator
*/

if( !is_user_logged_in() || !current_user_can( 'edit_posts' )){
	$site_url = get_site_url();
    $url = $site_url . "/wp-login.php";
    wp_redirect($url);
    exit;
}
get_header();

?>

<div id="content">

	<div class="row">
            
        <?php  get_template_part( 'parts/content', 'reportgenerator' ); ?>

	</div> <!-- end row -->

</div> <!-- end #content -->

<?php

	get_footer();

?>