<?php if(is_front_page()){
	$sidebar = 'Home';
}elseif(is_attachment()){
	$sidebar = 'Image';
}elseif(is_single()){
	$sidebar = 'Single';
}else{
	$sidebar = 'Default';
} ?>
<aside id="sidebar-<?php echo $sidebar; ?>" class="sidebar" role="complementary">
	<?php //check to see if widgets are present if not use 'Default' as a fallback.
	if(!dynamic_sidebar($sidebar)) {
		dynamic_sidebar('Default');
	}
	?>
</aside>