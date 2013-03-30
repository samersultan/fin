<?php $sidebars = array('Default');
if(is_front_page()){
	array_unshift($sidebars,'Home');
}elseif(is_attachment()){
	array_unshift($sidebars,'Image','Single');
}elseif(is_single()){
	array_unshift($sidebars,'Single');
}elseif(is_single()){
	array_unshift($sidebars,'Category');
}elseif(is_tax()){
	array_unshift($sidebars,'Tag', 'Category');
}elseif(is_archive()){
	array_unshift($sidebars,'Archive','Category');
}elseif(is_author()){
	array_unshift($sidebars,'Author', 'Single');
}elseif(is_search()){
	array_unshift($sidebars,'Search');
} ?>
<aside id="sidebar-<?php echo $sidebars[0]; ?>" class="sidebar" role="complementary">
	<?php //go through the $sidebars array until one is present
	foreach ($sidebars as $sidebar) {
		if(dynamic_sidebar($sidebar)) {
			break;
		}
	} ?>
</aside>