<?php
// Tell the TinyMCE editor to use a custom stylesheet
add_theme_support('editor_style');
add_editor_style('assets/css/editor-style.css');

/**
 * Edit Admin Menus
 *
 */
function fin_change_default_menus() {
	global $current_user;
	$current_user = wp_get_current_user();
	if($current_user->ID != 1) {
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
	global $current_user;
	$current_user = wp_get_current_user();
	if($current_user->ID != 1) {
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
 * Add Customize link to the admin menu
 *
 */
function fin_add_customize_menu() {
	// add the Customize link to the admin menu
	add_theme_page( 'Customize', 'Customize', 'edit_theme_options', 'customize.php' ); 
}
add_action ('admin_menu', 'fin_add_customize_menu');

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
	if ( get_option( 'default_comment_status' ) == 'closed' ) {
		remove_meta_box('dashboard_recent_comments', 'dashboard', 'core'); // Comments Panel
	}
	// remove_meta_box('dashboard_incoming_links', 'dashboard', 'core');  // Incoming Links Panel
	remove_meta_box('dashboard_plugins', 'dashboard', 'core');         // Plugins Panel

	// remove_meta_box('dashboard_quick_press', 'dashboard', 'core');  // Quick Press Panel
	remove_meta_box('dashboard_recent_drafts', 'dashboard', 'core');   // Recent Drafts Panel
	remove_meta_box('dashboard_primary', 'dashboard', 'core');         // 
	remove_meta_box('dashboard_secondary', 'dashboard', 'core');       //
}
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
	global $current_user;
	$current_user = wp_get_current_user();
	if($current_user->ID != 1) {
		add_action( 'init', create_function( '$a', "remove_action( 'init', 'wp_version_check' );" ), 2 );
		add_filter( 'pre_option_update_core', create_function( '$a', "return null;" ) );
	}
}
add_action('admin_init','fin_remove_update_alert');

/**
 * Remove/Add admin_bar menus
 *
 **/
function fin_change_admin_bar_menu() {
	if (is_admin_bar_showing() && $current_user->ID != 1) {
		global $wp_admin_bar;
		$wp_admin_bar->remove_menu('wp-logo');
    $wp_admin_bar->remove_menu('about');
    $wp_admin_bar->remove_menu('wporg');
    $wp_admin_bar->remove_menu('documentation');
    $wp_admin_bar->remove_menu('support-forums');
    $wp_admin_bar->remove_menu('feedback');
	  //$wp_admin_bar->remove_menu('view-site');
		    
		    
		//$wp_admin_bar->remove_menu('my-account');
		//$wp_admin_bar->remove_menu('my-account-with-avatar');
		//$wp_admin_bar->remove_menu('my-blogs');
		//$wp_admin_bar->remove_menu('get-shortlink');
		//$wp_admin_bar->remove_menu('edit');
		//$wp_admin_bar->remove_menu('new-content ');
		$wp_admin_bar->remove_menu('appearance');
		$wp_admin_bar->remove_menu('updates');
		if ( get_option( 'default_comment_status' ) == 'closed' ) {
			$wp_admin_bar->remove_menu('comments');
		}
		
		// Add Developer Link
		
		// Add Home_url logo link
	}
}
add_action('wp_before_admin_bar_render', 'fin_change_admin_bar_menu', 0);
/**
 * Remove Editor menu
 *
 */
function fin_remove_editor_menu() {
	global $current_user;
	$current_user = wp_get_current_user();
	if($current_user->ID != 1) {
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