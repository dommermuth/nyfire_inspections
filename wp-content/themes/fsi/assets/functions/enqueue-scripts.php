<?php
function site_scripts() {

    global $wp_styles; // Call global $wp_styles variable to add conditional wrapper around ie stylesheet the WordPress way

	//video background
	//wp_enqueue_script( 'video-bg-js', get_template_directory_uri() . '/assets/js/jquery.vide.min.js', ["jquery"], '6.2.3', true );

    // Adding scripts file in the footer
    wp_enqueue_script( 'site-js', get_template_directory_uri() . '/assets/js/scripts.js', ["jquery"], '', true );

    // Register main stylesheet
    wp_enqueue_style( 'site-css', get_template_directory_uri() . '/assets/css/style.min.css', [], '', 'all' );

	//wp_enqueue_script( 'recaptcha', 'https://www.google.com/recaptcha/api.js', ["jquery"], '', true );

    //slick slider enqueue only for valueprop template
    wp_enqueue_style( 'slick-css', '//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css', [], '', 'all' );
    wp_enqueue_script( 'slick', '//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js', [], '', true );

    //fancy box
    wp_enqueue_style( 'fancybox-css', '//cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.css', [], '', 'all' );
    wp_enqueue_script( 'fancybox', '//cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js', ["jquery"], '', true );

    //greensock for why choice page
	wp_enqueue_script( 'gsap', '//cdnjs.cloudflare.com/ajax/libs/gsap/1.19.1/TweenMax.min.js', ["jquery"], '', true );
    wp_enqueue_script( 'waypoints',  get_template_directory_uri() . '/assets/js/jquery.waypoints.min.js', ["jquery"], '', true );

    wp_enqueue_script( 'fontawesome', '//use.fontawesome.com/f1a322bc5d.js', [], '', true );
	//wp_enqueue_style( 'font-awesome-free', '//use.fontawesome.com/releases/v5.2.0/css/all.css' );


    //wp_enqueue_script( 'external',  get_template_directory_uri() . '/external_embeds/kp-maps.js', [], '', true );
    //make php vars accessible to JS
    /*
    $kp_custom = [
					'stylesheet_directory_uri' => get_stylesheet_directory_uri(),
					'ajaxurl' => admin_url('admin-ajax.php')
                  ];
	wp_localize_script( 'kp-app', 'js_info', $kp_custom );
    */

}
add_action('wp_enqueue_scripts', 'site_scripts', 999);

function admin_scripts() {
    wp_enqueue_script( 'scripts-admin',  get_template_directory_uri() . '/assets/js/scripts-admin.js', ["jquery"], '', true );
}

add_action( 'admin_enqueue_scripts', 'admin_scripts', 999 );