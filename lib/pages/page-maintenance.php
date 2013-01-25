<?php
// Check if page already exists
$existingPageTitles = array();
$existing_pages = get_pages();
if($existing_pages != ''){
	foreach ($existing_pages as $page) {
		$existingPageTitles[] = $page->post_title;
	}
}
if(!in_array('Maintenance', $existingPageTitles) && !in_array('Construction', $existingPageTitles) && !in_array('Under Construction', $existingPageTitles)) {
	$add_page = array(
		'post_type' => 'page',
		'post_status' => 'publish',
		'post_title' => 'Under Construction',
		'post_name' => 'maintenance',
		'post_content' => '<h3>Under Construction</h3>
		<h5>Please check back soon</h5>' 
	);
	$page = wp_insert_post($add_page);
}