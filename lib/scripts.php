<?php
/**
 * Scripts and Stylesheets
 *
 */
function fin_scripts() {
	// Custom Admin and Login CSS
	if(is_login() || is_admin() || is_admin_bar_showing()) {
		wp_enqueue_style('fin_admin', get_template_directory_uri() . '/assets/css/admin.css', false, null);
	}
	if(!is_login() && !is_admin()) {
		// load master stylesheet
		wp_enqueue_style('fin_app', get_template_directory_uri() . '/assets/css/app.css', false, null);
		
		// load style.css from child theme
		if (is_child_theme()) {
		  wp_enqueue_style('fin_child', get_stylesheet_uri(), false, null);
		}
				
		// jQuery is loaded in header.php using the same method from HTML5 Boilerplate:
		// Grab Google CDN's jQuery, with a protocol relative URL; fall back to local if offline
		// It's kept in the header instead of footer to avoid conflicts with plugins.
		if (!is_admin()) {
		  wp_deregister_script('jquery');
		  wp_register_script('jquery', '', '', '1.8.3', false);
		}
		
		// load foundation js
		wp_register_script('fin_foundation_js', get_template_directory_uri() . '/assets/js/foundation.min.js', false, null, false);
		wp_enqueue_script('fin_foundation_js');
		
		// load master js
		wp_register_script('fin_js', get_template_directory_uri() . '/assets/js/app.min.js', false, null, false);
		wp_enqueue_script('fin_js');
		
		// register orbit js
		wp_register_script('fin_orbit_js', get_template_directory_uri() . '/assets/js/orbit.min.js', false, null, false);
		
		// load wordpress comment reply if threaded comments are enabled
		if (is_single() && comments_open() && get_option('thread_comments')) {
		  wp_enqueue_script('comment-reply');
		}
	}
}
add_action('wp_enqueue_scripts','fin_scripts', 100);
add_action('admin_enqueue_scripts', 'fin_scripts', 100);
add_action('login_enqueue_scripts','fin_scripts', 10);

/**
 * Add Google Analytics to footer
 *
 */
function fin_analytics() {
	if(!current_user_can('edit_posts')) {
		$analyticsNumber = of_get_option('analytics');
		if($analyticsNumber != '' && $analyticsNumber != 'UA-#######-#') {
			echo "<script type='text/javascript'>
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', '$analyticsNumber;']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
</script>
";
		}
	}
}
add_action('wp_footer', 'fin_analytics');