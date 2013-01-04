<?php

/**
 * Scripts and Stylesheets
 *
 */
function fin_scripts() {
	// load master stylesheet
	wp_enqueue_style('fin_app', get_template_directory_uri() . '/assets/css/app.css', false, null);
	
	// load style.css from child theme
	if (is_child_theme()) {
	  wp_enqueue_style('fin_child', get_stylesheet_uri(), false, null);
	}
	
	// jQuery is loaded in header.php using the same method from HTML5 Boilerplate:
	// Grab Google CDN's jQuery, with a protocol relative URL; fall back to local if offline
	// It's kept in the header instead of footer to avoid conflicts with plugins.
	if (!is_admin()) {
	  wp_deregister_script('jquery');
	  wp_register_script('jquery', '', '', '1.8.3', false);
	}
	
	// load foundation js
	wp_register_script('fin_foundation_js', get_template_directory_uri() . '/assets/js/foundation.min.js', false, null, false);
	wp_enqueue_script('fin_foundation_js');
	// load master js
	wp_register_script('fin_js', get_template_directory_uri() . '/assets/js/app.js', false, null, false);
	wp_enqueue_script('fin_js');
	
	// load wordpress comment reply if threaded comments are enabled
	if (is_single() && comments_open() && get_option('thread_comments')) {
	  wp_enqueue_script('comment-reply');
	}
}
add_action('wp_enqueue_scripts','fin_scripts', 100);