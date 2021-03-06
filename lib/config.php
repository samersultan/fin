<?php
/**
 * Basic Config and Constants Setup After Installation
 *
 */
if(!function_exists('fin_init')) {
	function fin_init() {
		//Remove Default Tagline
		if(get_bloginfo('description') == 'Just another WordPress site') {
			update_option('blogdescription','');
		}
		
		// Allow shortcodes in widgets
		add_filter( 'widget_text', 'shortcode_unautop');
		add_filter( 'widget_text', 'do_shortcode', 11);
		
		// Change Uploads folder to /Assets
		update_option('uploads_use_yearmonth_folders', 0);
		update_option('upload_path', 'assets');
		update_option('upload_url_path', home_url() . '/assets');
		
		// Rewrite Permalink Structure
		update_option('category_base', '/site/');
		update_option('permalink_structure', '/%category%/%postname%/');
		
		// change start of week to Sunday
		update_option('start_of_week',0);
		
		// Change 'Uncategorized' to 'General'
		$category = get_term_by('id', '1', 'category');
		if($category) {
			$category->name = 'General';
			$category->slug = strtolower(str_replace('_', ' ', 'general'));
		}	
		wp_update_term( $category->term_id, 'category', array( 'slug' => $category->slug, 'name'=> $category->name ) );
		
		// Disable Smilies
		update_option('use_smilies', 0);
		
		// Default Comment Status
		update_option( 'default_comment_status', 'closed' );
		update_option( 'default_ping_status', 'closed' );
		
		// Set the size of the Post Editor
		update_option('default_post_edit_rows', 60);
		
		// Set the post revisions to 5 unless previously set to avoid DB bloat
		if (!defined('WP_POST_REVISIONS')) { define('WP_POST_REVISIONS', 3); }
		
		// Set Timezone
		//$timezone = "America/New_York";
		$timezone = "America/Chicago";
		//$timezone = "America/Denver";
		//$timezone = "America/Los_Angeles";
		update_option('timezone_string',$timezone);
		
		// Remove unecessary widget queries
		add_option( 'widget_pages', array ( '_multiwidget' => 1 ) );
		add_option( 'widget_calendar', array ( '_multiwidget' => 1 ) );
		add_option( 'widget_tag_cloud', array ( '_multiwidget' => 1 ) );
		add_option( 'widget_nav_menu', array ( '_multiwidget' => 1 ) );
	}
}
add_action('after_switch_theme', 'fin_init');

function filter_upload_dir($uploads){
	$uploads = array(
		'path' => HOME_PATH . 'assets',
		'url' => WP_BASE .'/assets',
		'subdir' => '',
		'basedir' => HOME_PATH . 'assets',
		'baseurl' => WP_BASE .'/assets',
		'error' => false
	);
	return $uploads;
}
add_filter( 'upload_dir', 'filter_upload_dir' );

/**
 * Basic Config and Constants Called Every Load
 *
 */
if(!function_exists('fin_setup')) {
	function fin_setup() {
		// Add post thumbnails (http://codex.wordpress.org/Post_Thumbnails)
		add_theme_support('post-thumbnails');
		
		// Add post formats (http://codex.wordpress.org/Post_Formats)
		add_theme_support('post-formats', array('gallery', 'image', 'video', 'audio'));
	}
}
add_action('after_setup_theme', 'fin_setup');

/**
 * Create default content for new posts and pages by pulling from /pages/page-default
 *
 */
if(!function_exists('fin_add_default_content')) {
	function fin_add_default_content($content) {
		$content = file_get_contents(locate_template('/lib/pages/page-default.php'));
		return $content;
	}
}
add_filter('default_content', 'fin_add_default_content');

/**
 * Change text for password protected areas
 *
 */
if(!function_exists('fin_change_password_text')) {
	function fin_change_password_text($content) {
		$content = str_replace(
			'This post is password protected. To view it please enter your password below:', 
			'This area is password protected. To view it please enter your password below:',
			$content);
		return $content;
	}
}
add_filter('the_content','fin_change_password_text');

/**
 * Set media sizes based on column and lineheight variables
 *
 */
if (!isset($content_width)) { $content_width = 1000; }
function fin_media_size() {
	$lineHeight = 25;
	$columnWidth = 50;
	$gutterWidth = 30;
	
	$thumb_w = ($columnWidth * 2) + ($gutterWidth * (2 - 1));
	$medium_w = ($columnWidth * 4) + ($gutterWidth * (4 - 1));
	$large_w = ($columnWidth * 8) + ($gutterWidth * (8 - 1));
	$xLarge_w = ($columnWidth * 12) + ($gutterWidth * (12 - 1));
	$embed_w = $xLarge_w;
	
	$thumb_h = $thumb_w;
	$medium_h = ceil(($medium_w * 2/3) / $lineHeight) * $lineHeight;
	$large_h = ceil(($large_w * 2/3) / $lineHeight) * $lineHeight;
	$xLarge_h = ceil(($xLarge_w * 2/3) / $lineHeight) * $lineHeight;
	$embed_h = $xLarge_h;
	
	add_image_size('xLarge', $xLarge_w, $xLarge_h);
	
	$sizes = array(
		array( 'name' => 'thumbnail_size_w', 'value' => $thumb_w ),
		array( 'name' => 'thumbnail_size_h', 'value' => $thumb_h ),
		array( 'name' => 'medium_size_w', 'value' => $medium_w ),
		array( 'name' => 'medium_size_h', 'value' => $medium_h ),
		array( 'name' => 'large_size_w', 'value' => $large_w ),
		array( 'name' => 'large_size_h', 'value' => $large_h ),
		array( 'name' => 'embed_size_w', 'value' => $embed_w ),
		array( 'name' => 'embed_size_h', 'value' => $embed_h )
	);
	foreach ( $sizes as $size ) {
		if ( get_option( $size['name'] ) != $size['value'] ) {
			update_option( $size['name'], $size['value'] );
		} else {
			$deprecated = ' ';
			$autoload = 'no';
			add_option( $size['name'], $size['value'] );
		}
	}
}
add_action('after_setup_theme', 'fin_media_size');

/**
 * Change the_category and get_the_category_list
 *
 */
function fin_the_category($list) {
	$list = str_replace('rel="category tag">', 'rel="category" class="meta-category tiny secondary button"><i class="icon-folder-close"></i> ', $list);
	return $list;
}
add_filter('the_category', 'fin_the_category');

/**
 * Change the_tags and get_tag_list
 *
 */
function fin_the_tags($list) {
	$list = str_replace('rel="tag">', 'rel="tag" class="meta-tag button tiny secondary button"><i class="icon-tags"></i> ', $list);
	return $list;
}
add_filter('the_tags', 'fin_the_tags');