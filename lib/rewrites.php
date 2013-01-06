<?php 

// Backwards compatibility for older than PHP 5.3.0
if (!defined('__DIR__')) { define('__DIR__', dirname(__FILE__)); }

// Define helper constants
$get_theme_name = explode('/themes/', get_template_directory());

define('WP_BASE',                   wp_base_dir());
define('THEME_NAME',                next($get_theme_name));
define('RELATIVE_PLUGIN_PATH',      str_replace(site_url() . '/', '', plugins_url()));
define('FULL_RELATIVE_PLUGIN_PATH', WP_BASE . '/' . RELATIVE_PLUGIN_PATH);
define('RELATIVE_CONTENT_PATH',     str_replace(site_url() . '/', '', content_url()));
define('THEME_PATH',                RELATIVE_CONTENT_PATH . '/themes/' . THEME_NAME);

if (stristr($_SERVER['SERVER_SOFTWARE'], 'apache') || stristr($_SERVER['SERVER_SOFTWARE'], 'litespeed') !== false) {

	// Show an admin notice if .htaccess isn't writable
	function fin_htaccess_writable() {
		if (!is_writable(get_home_path() . '.htaccess')) {
			if (current_user_can('manage_options')) {
				add_action('admin_notices', create_function('', "echo '<div class=\"error\"><p>" . sprintf(__('Please make sure your <a href="%s">.htaccess</a> file is writable ', 'roots'), admin_url('options-permalink.php')) . "</p></div>';"));
			}
		}
	}
	add_action('admin_init', 'fin_htaccess_writable');

	function fin_add_rewrites($content) {
	  // general rewrites
		global $wp_rewrite;
		$fin_new_non_wp_rules = array(
			'assets/css/(.*)'  => THEME_PATH . '/assets/css/$1',
			'assets/js/(.*)'   => THEME_PATH . '/assets/js/$1',
			'assets/img/(.*)'  => THEME_PATH . '/assets/img/$1',
			'assets/font/(.*)' => THEME_PATH . '/assets/font/$1',
			'plugins/(.*)'     => RELATIVE_PLUGIN_PATH . '/$1',
			'login'					   => 'wp-login.php',
			'admin'					   => 'wp-admin'
		);
		$wp_rewrite->non_wp_rules = array_merge($wp_rewrite->non_wp_rules, $fin_new_non_wp_rules);
		
		// insert contents of .htaccess-custom file
		$home_path = function_exists('get_home_path') ? get_home_path() : ABSPATH;
		$htaccess_file = $home_path . '.htaccess';
		$mod_rewrite_enabled = function_exists('got_mod_rewrite') ? got_mod_rewrite() : false;
		
		if ((!file_exists($htaccess_file) && is_writable($htaccess_file) && $wp_rewrite->using_mod_rewrite_permalinks()) || is_writable($htaccess_file)) {
		  if ($mod_rewrite_enabled) {
		    $customRules = extract_from_markers($htaccess_file, 'customRules');
		    if ($customRules === array()) {
		      $filename = dirname(__FILE__) . '/htaccess-custom';
		      return insert_with_markers($htaccess_file, 'customRules', extract_from_markers($filename, 'customRules'));
		    }
		  }
		}
		
		return $content;
	}

	function fin_clean_urls($content) {
		if (strpos($content, FULL_RELATIVE_PLUGIN_PATH) === 0) {
			return str_replace(FULL_RELATIVE_PLUGIN_PATH, WP_BASE . '/plugins', $content);
		} else {
			return str_replace('/' . THEME_PATH, '', $content);
		}
	}

	if (!is_multisite()) {
		add_action('generate_rewrite_rules', 'fin_add_rewrites');
	}
	if (!is_admin()) {
		$tags = array(
			'plugins_url',
			'bloginfo',
			'stylesheet_directory_uri',
			'template_directory_uri',
			'script_loader_src',
			'style_loader_src'
		);
		add_filters($tags, 'fin_clean_urls');
	}
}