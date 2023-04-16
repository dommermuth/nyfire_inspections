<?php
//Add login CSS to customize login screen
function my_login_logo_one() {
?>
<style type="text/css">

    body.login {
        background-color: #F5F5F5;
    }

    body.login div#login h1 a {
        background-image: url(/wp-content/themes/fsi/assets/images/logo-nyfs.png);
        padding-bottom: 10px;
        width: 200px;
        background-size: auto;
        padding-bottom: 60px;
    }

    body.login div#login .button-primary {
        background: #081932;
        border-color: #081932;
        color: #FFF;
        text-shadow: none;
        border-radius:0;
    }

    body.login div#login input {
        border-color: #ccc;
    }

    body.login div#login .message {
        border-left: 4px solid #17afe0;
    }

</style>
<?php
}
add_action( 'login_enqueue_scripts', 'my_login_logo_one' );


add_filter( 'login_headerurl', 'custom_loginlogo_url' );
function custom_loginlogo_url($url) {
	return get_site_url();
}

add_action('after_setup_theme', 'remove_admin_bar');
function remove_admin_bar() {
	if (!current_user_can('administrator') && !is_admin()) {
		show_admin_bar(false);
	}
}

function my_login_redirect( $redirect_to, $request, $user ) {
    //validating user login and roles
    $cur_url = "http" . (($_SERVER['SERVER_PORT'] == 443) ? "s" : "") . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    $site_url = get_site_url();
    $account_management = $site_url . "/account-management";
    $report_generator = $site_url . "/report-generator";

    if (isset($user->roles) && is_array($user->roles)) {
        //is this a gold plan subscriber?
        //var_dump($user->roles);
        if (in_array('editor', $user->roles)) {
            $redirect_to = $report_generator;
        } elseif(in_array('administrator', $user->roles)) {
            $redirect_to = $report_generator;//admin_url();
        }else {
            //all other members
            $redirect_to = $site_url;
        }
    }
    return $redirect_to;
}
add_filter( 'login_redirect', 'my_login_redirect', 10, 3 );


add_action( 'wp_logout', 'auto_redirect_external_after_logout');
function auto_redirect_external_after_logout(){
    wp_redirect( '/' );
    exit();
}

//add hook to redirect the user back to the elementor login page if the login failed
add_action( 'wp_login_failed', 'elementor_form_login_fail' );
function elementor_form_login_fail( $username ) {
    $referrer = $_SERVER['HTTP_REFERER'];  // where did the post submission come from?
    // if there's a valid referrer, and it's not the default log-in screen
    if ( !empty($referrer) && !strstr($referrer,'wp-login') && !strstr($referrer,'wp-admin') ) {
        //redirect back to the referrer page, appending the login=failed parameter and removing any previous query strings
        //maybe could be smarter here and parse/rebuild the query strings from the referrer if they are important
        wp_redirect(preg_replace('/\?.*/', '', $referrer) . '/?login=failed' );
        exit;
    }
}