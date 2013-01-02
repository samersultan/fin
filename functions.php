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

if(current_user_can('edit_posts')) {
	require_once locate_template('/lib/admin.php');    // Custom Admin Scripts
}

function fin_add_pages(){
	$defaultPages = array('about', 'contact', 'copyrights', 'home', 'privacy');
	foreach ($defaultPages as $defaultPage) {
		require_once locate_template('/lib/pages/page-' . $defaultPage . '.php');
	}
}
add_action('after_switch_theme', 'fin_add_pages');