<?php
/**
 * Scripts and Stylesheets
 *
 */
function fin_scripts() {
	// Load Google Fonts
	$options = get_option('fin_theme_options');
	if($options['text_font'] != '' || $options['heading_font'] != '') {
		wp_enqueue_style( 'fin_fonts', 'http://fonts.googleapis.com/css?family=' . str_replace(" ", "+", $options['heading_font'] ) . '|' . str_replace(" ", "+", $options['text_font'] ) , false, null);
	}
	
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
		
		// load master js
		wp_register_script('fin_js', get_template_directory_uri() . '/assets/js/app.min.js', array( 'jquery' ), null, false);
		wp_enqueue_script('fin_js');
		
		// load wordpress comment reply if threaded comments are enabled
		if (is_singular() && comments_open() && get_option('thread_comments')) {
		  wp_enqueue_script('comment-reply');
		}
		
		// regsiter carousel script
		wp_register_script('fin_carousel', get_template_directory_uri() . '/assets/js/carousel.js', null, true);
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
		$options = get_option('fin_theme_options');
		$analyticsNumber = $options['analytics'];
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