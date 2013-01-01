<?php
// Check if page already exists
$existing_pages = get_pages();
foreach ($existing_pages as $page) {
  $existingPageTitles[] = $page->post_title;
}
if(!in_array('Contact', $existingPageTitles)) {
	$add_page = array(
		'post_type' => 'page',
		'post_status' => 'publish',
		'post_title' => 'Contact',
		'post_content' => 'Thank you for your interest in '. get_bloginfo('name','Display') . '. Please fill out the form below to contact '. get_bloginfo('name','Display') . '. [contact]'
	);
	$page = wp_insert_post($add_page);
	
	// Add to 'primary' menu
	fin_add_to_menu($page,'primary_menu');
}
?>