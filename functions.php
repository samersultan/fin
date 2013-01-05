<?php 
/**
 * functions.php
 *
 * Required Files
 *
 */
require_once locate_template('/lib/utils.php');      // Base templating System and Other Utilities
require_once locate_template('/lib/config.php');     // Basic Config and Constants
require_once locate_template('/lib/cleanup.php');    // Cleanup Wordpress Stuffs
require_once locate_template('/lib/rewrites.php');   // Custom .htaccess rewrites
require_once locate_template('/lib/options.php');    // Additional Theme Options
require_once locate_template('/lib/sidebar.php');    // Create Sidebars
require_once locate_template('/lib/post-types.php'); // Custom Post Types
require_once locate_template('/lib/nav.php');        // Custom Nav Menus
require_once locate_template('/lib/shortcodes.php'); // Custom Shortcodes
require_once locate_template('/lib/scripts.php');    // Enqueue All Scripts

/**
 * Custom Admin CSS
 *
 */
function fin_admin_css() {
	wp_enqueue_style('fin_app', get_template_directory_uri() . '/assets/css/admin.css');
}
// calling it only on admin pages
add_action('admin_head', 'fin_admin_css');
// calling it when logged in as admin
add_action('wp_head', 'fin_admin_css');
// calling it for login
add_action('login_head', 'fin_admin_css');
/**
 * More Admin Scripts
 *
 */
if(current_user_can('edit_posts')) {
	require_once locate_template('/lib/admin.php');    // Custom Admin Scripts
}

/**
 * Create Default Pages
 *
 */
function fin_add_pages(){
	$defaultPages = array('about', 'contact', 'copyrights', 'home', 'privacy');
	foreach ($defaultPages as $defaultPage) {
		require_once locate_template('/lib/pages/page-' . $defaultPage . '.php');
	}
}
add_action('after_switch_theme', 'fin_add_pages');