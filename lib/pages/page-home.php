<?php
// Check if page already exists
$existingPageTitles = array();
$existing_pages = get_pages();
if($existing_pages != ''){
	foreach ($existing_pages as $page) {
		$existingPageTitles[] = $page->post_title;
	}
}
if(!in_array('Home', $existingPageTitles)) {
	$add_page = array(
		'post_type' => 'page',
		'post_status' => 'publish',
		'post_title' => 'Home',
		'post_content' => 'Hello! Welcome to the website for ' . get_bloginfo('name') . '. For more information about ' . get_bloginfo('name') . ' please visit <a href="' .  get_bloginfo('url') . '/about" title="about">this page</a>. If you have questions or would like to send a message please visit the <a href="' . get_bloginfo('url') . '/contact" title="about">contact page</a>. Thanks!' 
	);
	$page = wp_insert_post($add_page);
	// Set Home as Front Page
	$home = get_page_by_title('Home');
	update_option('show_on_front', 'page');
	update_option('page_on_front', $home->ID);
	
	$home_menu_order = array(
	  'ID' => $home->ID,
	  'menu_order' => -1
	);
	wp_update_post($home_menu_order);
	
	// Add to 'primary' menu
	fin_add_to_menu($page,'primary_menu');
}
?>