<?php

//function to show button with favorites url
function get_logged_in_nav() {



	$html = '<!-- comes from custom_short_codes.php --><div class="elementor-widget-container">';

    if( is_user_logged_in() && (current_user_can('manage_options'))){

    	$html .= '<a href="/wp-admin" class="elementor-item">Account Management</a>';


    } else {
		$html .= '<a href="/wp-login.php" class="elementor-button-link elementor-button elementor-size-sm" role="button">
						<span class="elementor-button-content-wrapper">
						<span class="elementor-button-text">LOG INasdf</span>
					</span>
					</a>';
	}

	$html .= '</div>';

	return $html;
}
add_shortcode('logged_in_nav', 'get_logged_in_nav');