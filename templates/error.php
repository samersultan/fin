<header>
	<div data-alert class="alert-box alert">
		<a href="#" type="button" class="close">&times;</a>
		<h5><?php _e( 'It appears that the page you are looking for is not there anymore.'); ?></h5>
		<h5><?php _e('Sorry about the inconvenience.'); ?></h5>
	</div>
</header>
<section class="hentry page single full">
	<p><?php _e('Please try the following:', 'fin'); ?></p>
	<ul>
	  <li><?php _e('Check your spelling', 'fin'); ?></li>
	  <li><?php printf(__('Return to the <a href="%s">home page</a>', 'fin'), home_url()); ?></li>
	  <li><?php _e('Click the <a href="javascript:history.back()">Back</a> button', 'fin'); ?></li>
	  <li>or search for the content you would like: <br><?php get_search_form(); ?></li>
	</ul>
</section>
<script type="text/javascript">
	// focus on search field after it has loaded
	document.getElementById('s') && document.getElementById('s').focus();
</script>
</section>