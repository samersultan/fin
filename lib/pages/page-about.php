<?php
// Check if page already exists
$existing_pages = get_pages();
foreach ($existing_pages as $page) {
  $existingPageTitles[] = $page->post_title;
}
if(!in_array('About', $existingPageTitles) && !in_array('About Us', $existingPageTitles) && !in_array('About Me', $existingPageTitles)) {
	$add_page = array(
		'post_type' => 'page',
		'post_status' => 'publish',
		'post_title' => 'About',
		'post_content' => '<h4>About '. get_bloginfo('name','Display') . '</h4>
		<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum consequat, orci ac laoreet cursus, dolor sem luctus lorem, eget consequat magna felis a magna. Aliquam scelerisque condimentum ante, eget facilisis tortor lobortis in. In interdum venenatis justo eget consequat. Morbi commodo rhoncus mi nec pharetra. </p>
		<h4>About This Site</h4>
		<p>Aliquam erat volutpat. Mauris non lorem eu dolor hendrerit dapibus. Mauris mollis nisl quis sapien posuere consectetur. Nullam in sapien at nisi ornare bibendum at ut lectus. Pellentesque ut magna mauris. Nam viverra suscipit ligula, sed accumsan enim placerat nec. Cras vitae metus vel dolor ultrices sagittis.</p>' 
	);
	$page = wp_insert_post($add_page);
	
	// Add to 'primary' menu
	fin_add_to_menu($page,'primary_menu');
}
?>