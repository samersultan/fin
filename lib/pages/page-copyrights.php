<?php
// Check if page already exists
$existing_pages = get_pages();
foreach ($existing_pages as $page) {
  $existingPageTitles[] = $page->post_title;
}
if(!in_array('Copyright', $existingPageTitles) && !in_array('Copyright', $existingPageTitles) ) {
	$add_page = array(
		'post_type' => 'page',
		'post_status' => 'publish',
		'post_title' => 'Copyrights',
		'post_content' => "All content is copyrighted. Please don't steal the images or text from this website. Thanks you." 
	);
	$page = wp_insert_post($add_page);
	
	// Add to 'secondary' menu
	fin_add_to_menu($page,'secondary_menu');
}
?>