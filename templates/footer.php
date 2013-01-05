<?php if(is_active_sidebar('Footer')) { ?>
	<aside class="sidebar footer row">
		<?php dynamic_sidebar('Footer'); ?>
	</aside>
<?php } ?>
<footer id="main-info" role="contentinfo" class="row">
	<p><a href="<?php echo esc_url(get_permalink(get_page_by_title( 'Copyrights'))); ?>">&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?></a></p>
	<?php /* Developer Info */
	$themeData = wp_get_theme();
	$developerName =$themeData->Author;
	if($developerName != ''){
		echo '<p>Built by: ' . $developerName . '</p>';
	} ?>
</footer>

<?php wp_footer(); ?>