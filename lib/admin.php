<?php
/**
 * Custom Admin CSS
 *
 */
function fin_admin_css() {
	if (is_admin_bar_showing()) {
		echo '<link rel="stylesheet" href="' . get_template_directory_uri() . '/assets/css/admin.css">';
	}
}
// calling it only on admin pages
add_action('admin_head', 'fin_admin_css');
// calling it when logged in as admin
add_action('wp_head', 'fin_admin_css');
// Tell the TinyMCE editor to use a custom stylesheet
add_editor_style('assets/css/editor-style.css');

/**
 * Edit Admin Menus
 *
 */
function fin_change_default_menus() {
	if(!current_user_can('manage_options')) {
		global $menu;
	  $restricted = array(
	  	//__('Links'),
	  	__('Comments'),
	  	__('Media'),
	  	__('Plugins'));
	  	//__('Tools'),
	  	//__('Users'));
	  end ($menu);
	  while (prev($menu)){
	    $value = explode(' ',$menu[key($menu)][0]);
	    if(in_array($value[0] != NULL?$value[0]:"" , $restricted)){
	      unset($menu[key($menu)]);
	   	}
		}
	}
}
add_action('admin_menu', 'fin_change_default_menus');

/**
 * Edit Admin Submenus
 *
 */
function fin_change_default_submenus() {
	if(!current_user_can('manage_options')) {
	  global $submenu;
	  unset($submenu['index.php'][10]); // Removes 'Updates'.
	  unset($submenu['themes.php'][5]); // Removes 'Themes'.
	  unset($submenu['options-general.php'][15]); // Removes 'Writing'.
	  // unset($submenu['options-general.php'][25]); // Removes 'Discussion'.
	  // unset($submenu['edit.php'][16]); // Removes 'Tags'. 
	}
}
add_action('admin_menu', 'fin_change_default_submenus');

/**
 * Remove Wordpress Welcome Panel
 *
 */
function fin_remove_welcome_panel() {
	update_user_meta( get_current_user_id(), 'show_welcome_panel', false );
}
add_action('wp_dashboard_setup', 'fin_remove_welcome_panel');

/**
 * Edit Default Dashboard Panels
 *
 */
function fin_change_default_dashboard_panels() {
	// remove_meta_box('dashboard_right_now', 'dashboard', 'core');    // Right Now Panel
	//remove_meta_box('dashboard_recent_comments', 'dashboard', 'core'); // Comments Panel
	//remove_meta_box('dashboard_incoming_links', 'dashboard', 'core');  // Incoming Links Panel
	remove_meta_box('dashboard_plugins', 'dashboard', 'core');         // Plugins Panel

	// remove_meta_box('dashboard_quick_press', 'dashboard', 'core');  // Quick Press Panel
	remove_meta_box('dashboard_recent_drafts', 'dashboard', 'core');   // Recent Drafts Panel
	remove_meta_box('dashboard_primary', 'dashboard', 'core');         // 
	remove_meta_box('dashboard_secondary', 'dashboard', 'core');       //
}
// Remove the dashboard panels
add_action('admin_menu', 'fin_change_default_dashboard_panels');

/**
 * Checks to see if your tagline is set to the default and shows an admin notice to update it
 * Throw this in function.php for your theme
 */
function fin_check_tagline() {
	if(get_option('blogdescription') == 'Just another WordPress site' || get_option('blogdescription') == '') {
		add_action('admin_notices', create_function( '', "echo '<div class=\"error\"><p>".sprintf(__('Please update your <a href="%s">Tagline</a>', 'bb'), admin_url('options-general.php'))."</p></div>';" ));
	}
}
add_action('admin_init', 'fin_check_tagline');

/**
 * Remove Update Alert
 *
 */
function fin_remove_update_alert() {
	if(!current_user_can('manage_options')) {
		add_action( 'init', create_function( '$a', "remove_action( 'init', 'wp_version_check' );" ), 2 );
		add_filter( 'pre_option_update_core', create_function( '$a', "return null;" ) );
	}
}
add_action('admin_init','fin_remove_update_alert');
/**
 * Remove Editor menu
 *
 */
function fin_remove_editor_menu() {
	if(!current_user_can('manage_options')) {
	  remove_action('admin_menu', '_add_themes_utility_last', 101);
	}
}
add_action('_admin_menu', 'fin_remove_editor_menu', 1);

/**
 * Remove Meta Boxes
 *
 */
function fin_remove_meta_boxes() {
  // Removes meta boxes from Posts 
  remove_meta_box('postcustom','post','normal');
  remove_meta_box('trackbacksdiv','post','normal');
  //remove_meta_box('commentstatusdiv','post','normal');
  remove_meta_box('commentsdiv','post','normal');
  // remove_meta_box('tagsdiv-post_tag','post','normal');
  remove_meta_box('postexcerpt','post','normal');
  // Removes meta boxes from pages 
  remove_meta_box('postcustom','page','normal');
  remove_meta_box('trackbacksdiv','page','normal');
  remove_meta_box('commentstatusdiv','page','normal');
  remove_meta_box('commentsdiv','page','normal'); 
}
add_action('admin_init','fin_remove_meta_boxes');

/**
 * Change Backend Footer
 *
 */
function fin_change_admin_footer() {
	$themeData = wp_get_theme();
	$developerName =$themeData->Author;
	if($developerName != ''){
		return '<p>Built by: ' . $developerName . '</p>';
	}else {
		return '';
	}
}
add_filter('admin_footer_text', 'fin_change_admin_footer');

/**
 * Remove Version Info From Admin Footer
 *
 */
function fin_change_version_footer() {
	return ' ';
}
add_filter('update_footer','fin_change_version_footer',11);