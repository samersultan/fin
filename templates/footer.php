<footer id="main-info" role="contentinfo">
	<?php dynamic_sidebar('sidebar-footer'); ?>
	<p><a href="<?php echo esc_url(get_permalink(get_page_by_title( 'Copyrights'))); ?>">&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?></a></p>
	<?php /* Developer Info */
	$themeData = wp_get_theme();
	$developerName =$themeData->Author;
	if($developerName != ''){
		echo '<p>Built by: ' . $developerName . '</p>';
	} ?>
</footer>

<?php wp_footer(); ?>