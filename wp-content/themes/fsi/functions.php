<?php

//keep user logged in for long time
add_filter('auth_cookie_expiration', function(){
    return YEAR_IN_SECONDS * 2;
});

function _custom_nav_menu_item( $title, $url, $order, $parent = 0 ){
  $item = new stdClass();
  $item->ID = 1000000 + $order + $parent;
  $item->db_id = $item->ID;
  $item->title = $title;
  $item->url = $url;
  $item->menu_order = $order;
  $item->menu_item_parent = $parent;
  $item->type = '';
  $item->object = '';
  $item->object_id = '';
  $item->classes = array();
  $item->target = '';
  $item->attr_title = '';
  $item->description = '';
  $item->xfn = '';
  $item->status = '';
  return $item;
}

add_filter( 'wp_get_nav_menu_items', 'custom_nav_menu_items', 20, 2 );

function custom_nav_menu_items( $items, $menu ){
		// only add profile link if user is logged in

	if( get_current_blog_id() != 1){
		if( is_user_logged_in() && current_user_can( 'edit_posts' )){
			if ( get_current_user_id() ){
				//$items[] =_custom_nav_menu_item( 'My Profile', get_author_posts_url( get_current_user_id() ), 3 ); 
				$items = [];
				$items[] =_custom_nav_menu_item( '<i class="fa fa-file"></i>&nbsp;Home', "/", 1 );
				$items[] =_custom_nav_menu_item( '<i class="fa fa-file"></i>&nbsp;Reports', home_url().'/report-generator/', 2 ); 
				$items[] =_custom_nav_menu_item( '<i class="fa fa-user"></i>&nbsp;Account', home_url().'/wp-admin/', 3 ); 
				$items[] =_custom_nav_menu_item( '<i class="fa fa-sign-out"></i>&nbsp;Logout', wp_logout_url( '/'), 4 );
			}
		}else{	
			$items = [];
			$items[] =_custom_nav_menu_item( '<i class="fa fa-sign-in"></i>&nbsp;Login', home_url().'/wp-login.php', 3 ); 
		}
	}else{
		$items = [];
		$items[] =_custom_nav_menu_item( '<i class="fa fa-file"></i>&nbsp;Home', "/", 0 );
		$blogs = get_sites();
		$count = 1;
		foreach( $blogs as $b ){
			if($count == 1) { $count++; continue; }
			$items[] =_custom_nav_menu_item( '<i class="fa fa-file"></i>&nbsp;'. strtoupper(str_replace("/", "", $b->path)), $b->path, $count );
			$count++;
		} 
		
	}
	
	return $items;
}

//this was not completed -
//include  get_template_directory().'/assets/functions/db_import_fix.php';

include  get_template_directory().'/assets/functions/acf.php';

include  get_template_directory().'/assets/functions/ajax.php';

include  get_template_directory().'/assets/functions/shortcodes.php';

require_once(get_template_directory().'/assets/functions/login.php');

require_once(get_template_directory().'/assets/functions/misc.php');

add_theme_support( 'menus' );
//require_once(get_template_directory().'/assets/functions/theme-support.php');

// WP Head and other cleanup functions
require_once(get_template_directory().'/assets/functions/cleanup.php');

// Register scripts and stylesheets
require_once(get_template_directory().'/assets/functions/enqueue-scripts.php');

// Register custom menus and menu walkers
//require_once(get_template_directory().'/assets/functions/menu.php');

// Register sidebars/widget areas
//require_once(get_template_directory().'/assets/functions/sidebar.php');

// Remove 4.2 Emoji Support
require_once(get_template_directory().'/assets/functions/disable-emoji.php');

// Replace 'older/newer' post links with numbered navigation
//require_once(get_template_directory().'/assets/functions/page-navi.php');

// Adds support for multiple languages
//require_once(get_template_directory().'/assets/translation/translation.php');

// Adds site styles to the WordPress editor
//require_once(get_template_directory().'/assets/functions/editor-styles.php');

// Related post function - no need to rely on plugins
// require_once(get_template_directory().'/assets/functions/related-posts.php');

// Use this as a template for custom post types
// require_once(get_template_directory().'/assets/functions/custom-post-type.php');

// Customize the WordPress login menu
// require_once(get_template_directory().'/assets/functions/login.php');

// Customize the WordPress admin
// require_once(get_template_directory().'/assets/functions/admin.php');
