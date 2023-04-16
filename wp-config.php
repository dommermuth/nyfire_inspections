<?php

/**
 * This config file is yours to hack on. It will work out of the box on Pantheon
 * but you may find there are a lot of neat tricks to be used here.
 *
 * See our documentation for more details:
 *
 * https://pantheon.io/docs
 */

/**
 * Pantheon platform settings. Everything you need should already be set.
 */
if (file_exists(dirname(__FILE__) . '/wp-config-local.php')){
	# IMPORTANT: ensure your local config does not include wp-settings.php
	require_once(dirname(__FILE__) . '/wp-config-local.php');
} else {
	define( 'DB_NAME', 'fsi_inspections' );
	define( 'DB_USER', 'inspect01' );
	define( 'DB_PASSWORD', 'jxyU2123*' );
	define( 'DB_HOST', 'localhost' );

	/** Database Charset to use in creating database tables. */
	define( 'DB_CHARSET', 'utf8' );

	/** The Database Collate type. Don't change this if in doubt. */
	define( 'DB_COLLATE', '' );

	/**
	 * Authentication Unique Keys and Salts.
	 *
	 * Change these to different unique phrases!
	 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
	 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
	 *
	 * @since 2.6.0
	 */
	define( 'AUTH_KEY', '+?Ig+qD.[n*2,Ki8GIi(h0|+dFB-]v@{vskN-I]J@*sjtJ6C?Kf2+#{@wO(J<NfD' );
	define( 'SECURE_AUTH_KEY', ':,%M0WgZPx@},j@#a[!a-ScA 12pSXWaka5hoR2q-j._O-Tbcog$9qGvU[HQE%dF' );
	define( 'LOGGED_IN_KEY', 'e|rhh+fY<~!R9e`gi/4xk@W,2)/b#Q*qM.fENoS0=i/,?R3*HGi;gDm<.4/Pjb:c' );
	define( 'NONCE_KEY', 'yubg60,<vHefw}8prxXfm0y9Uvav:2c>t4*i-<|K #517K_|wbDgZ5!w9H((y>+$' );
	define( 'AUTH_SALT', 'Q%tKsJ.|}X,bK^Z+M|Suga~X^yw.E4YWrZ,c:S#INl2R=?)s_ZyrQG$NMn1gd5MT' );
	define( 'SECURE_AUTH_SALT', 'hthg49i#N}iP[Ss~<^Z[x{G@%pg8nH1Y[}V1+AJ 1a!U+5d`aG`|6[wpX2fjT-:O' );
	define( 'LOGGED_IN_SALT', 'kS)Q-9| ]S#+WOJ;UYAGjyF0ih,VZ((,jXAF0|-C6Erw&w*&qY]pl]+2hBee213a' );
	define( 'NONCE_SALT', 'SwaPzk#%7M2fE3|:=5KQMYbK (oXyg<,0BFJ][y!6)H9;rsSs030-RpX5~%t3w40' );

	$table_prefix = 'wp_';
	define('FS_METHOD','direct');
	define( 'MULTISITE', true );
	define( 'SUBDOMAIN_INSTALL', false );
	define( 'DOMAIN_CURRENT_SITE', 'inspections.nyfiresafe.com' );
	define( 'PATH_CURRENT_SITE', '/' );
	define( 'SITE_ID_CURRENT_SITE', 1 );
	define( 'BLOG_ID_CURRENT_SITE', 1 );

	

}

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';