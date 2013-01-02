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

<?php /* Analytics */
if(!current_user_can('administrator')) {
	$analyticsNumber = get_option('analytics');
	if($analyticsNumber != '' && $analyticsNumber != 'UA-#######-#') { ?>
		<script type="text/javascript">
		  var _gaq = _gaq || [];
		  _gaq.push(['_setAccount', '<?php echo $analyticsNumber; ?>']);
		  _gaq.push(['_trackPageview']);
		
		  (function() {
		    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
		  })();
			</script>
	<?php }
} ?>

<?php wp_footer(); ?>