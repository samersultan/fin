<?php 
/**
 * functions.php
 *
 * Required Files
 *
 */

// Backwards compatibility for older than PHP 5.3.0
if (!defined('__DIR__')) { define('__DIR__', dirname(__FILE__)); }

// Define helper constants
$get_theme_name = explode('/themes/', get_template_directory());

/**
 * Duplicate function because WP wants to ignore file.php sometimes?
 * Get the absolute filesystem path to the root of the WordPress installation
 *
 * @since 1.5.0
 *
 * @uses get_option
 * @return string Full filesystem path to the root of the WordPress installation
 */
function fin_get_home_path() {
	$home = get_option( 'home' );
	$siteurl = get_option( 'siteurl' );
	if ( ! empty( $home ) && 0 !== strcasecmp( $home, $siteurl ) ) {
		$wp_path_rel_to_home = str_ireplace( $home, '', $siteurl ); /* $siteurl - $home */
		$pos = strripos( str_replace( '\\', '/', $_SERVER['SCRIPT_FILENAME'] ), trailingslashit( $wp_path_rel_to_home ) );
		$home_path = substr( $_SERVER['SCRIPT_FILENAME'], 0, $pos );
		$home_path = trailingslashit( $home_path );
	} else {
		$home_path = ABSPATH;
	}

	return $home_path;
}

define('HOME_PATH' , 						function_exists('get_home_path') ? get_home_path() : fin_get_home_path()); //file://site_folder/
define('WP_BASE', 							home_url()); //http://site.com
define('THEME_NAME', 						next($get_theme_name));
define('CONTENT_PATH',       		str_replace(WP_BASE . '/', '', content_url()) ); // 'content'
define('THEME_PATH',            CONTENT_PATH . '/themes/' . THEME_NAME ); // 'content/themes/fin'
define('PLUGIN_PATH',        		str_replace(WP_BASE . '/', '', plugins_url()) . '/' ); // 'content/plugins/'
define('FULL_PLUGIN_PATH',   		WP_BASE . '/' . PLUGIN_PATH); // http://site.com/content/plugins/'
define('INCLUDES_PATH',      		str_replace(WP_BASE . '/', '', includes_url())); // 'wp/wp-includes/'
define('FULL_INCLUDES_PATH',		WP_BASE . '/' . INCLUDES_PATH); // http://site.com/wp/wp-includes/'

require_once locate_template('/lib/utils.php');      // Base templating System and Other Utilities
require_once locate_template('/lib/config.php');     // Basic Config and Constants
require_once locate_template('/lib/cleanup.php');    // Cleanup Wordpress Stuffs
require_once locate_template('/lib/rewrites.php');   // Custom .htaccess rewrites
require_once locate_template('/lib/options.php');    // Additional Theme Options
require_once locate_template('/lib/sidebars.php');   // Create Sidebars
require_once locate_template('/lib/post-types.php'); // Custom Post Types
require_once locate_template('/lib/nav.php');        // Custom Nav Menus
require_once locate_template('/lib/comments.php');   // Custom Comment Structure
require_once locate_template('/lib/scripts.php');    // Enqueue All Scripts
require_once locate_template('/lib/shortcodes.php'); // Custom Shortcodes

/**
 * Load Admin Scripts
 *
 */
if(current_user_can('edit_posts')) {
	require_once locate_template('/lib/admin.php');    // Custom Admin Scripts
}

/**
 * Create Default Pages
 *
 */
if(!function_exists('fin_add_pages')) {
	function fin_add_pages(){
		$defaultPages = array('home', 'about', 'contact', 'copyrights', 'privacy', 'maintenance', 'sitemap');
		foreach ($defaultPages as $defaultPage) {
			require_once locate_template('/lib/pages/page-' . $defaultPage . '.php');
		}
	}
}
add_action('after_switch_theme', 'fin_add_pages');

// add woocommerce support
if(class_exists( 'Woocommerce' )) {
	add_theme_support('woocommerce');
	require_once('woocommerce/woo-functions.php');
}
