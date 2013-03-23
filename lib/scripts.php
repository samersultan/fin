<?php
/**
 * Scripts and Stylesheets
 *
 */
function fin_scripts() {
	// Load Google Fonts
	$options = get_option('fin_theme_options');
	$navFont = str_replace(' ', '+', $options['nav_font'] );
	$headingFont = str_replace(' ', '+', $options['heading_font'] );
	$textFont = str_replace(' ', '+', $options['text_font'] );
	if($headingFont != '' || $textFont != ''|| $navFont != '') {
		$fontCSS = 'http://fonts.googleapis.com/css?family=';
		if($navFont != '') {
			$fontCSS .= $navFont;
			if($headingFont != '' || $textFont != '') {
				$fontCSS .= '|';
			}
		}
		if($headingFont != '') {
			$fontCSS .= $headingFont;
			if($textFont != '') {
				$fontCSS .= '|';
			}
		}
		if($textFont != '') {
			$fontCSS .= $textFont;
		}
		wp_enqueue_style( 'fin_fonts', $fontCSS , false, null);
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
		
		// jQuery is loaded using the same method from HTML5 Boilerplate:
		// Grab Google CDN's latest jQuery with a protocol relative URL; fallback to local if offline
		// It's kept in the header instead of footer to avoid conflicts with plugins.
		if (!is_admin()) {
			wp_deregister_script('jquery');
			wp_register_script('jquery', '//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js', false, null, false);
		}
		
		// load master js
		wp_register_script('fin_js', get_template_directory_uri() . '/assets/js/app.min.js', array( 'jquery' ), null, true);
		wp_enqueue_script('fin_js');
		
		// load wordpress comment reply if threaded comments are enabled
		if (is_singular() && comments_open() && get_option('thread_comments')) {
		  wp_enqueue_script('comment-reply');
		}
		
		// regsiter orbit script
		wp_register_script('fin_orbit', get_template_directory_uri() . '/assets/js/orbit.min.js', array( 'jquery', 'fin_js' ), null, true);
	}
}
add_action('wp_enqueue_scripts','fin_scripts', 100);
add_action('admin_enqueue_scripts', 'fin_scripts', 100);
add_action('login_enqueue_scripts','fin_scripts', 10);

/**
 * jquery fallback
 * http://wordpress.stackexchange.com/a/12450
 */
function fin_jquery_local_fallback($src, $handle) {
	static $add_jquery_fallback = false;
	
	if ($add_jquery_fallback) {
		echo '<script>window.jQuery || document.write(\'<script src="' . get_template_directory_uri() . '/assets/js/jquery-1.9.1.min.js"><\/script>\')</script>' . "\n";
		$add_jquery_fallback = false;
	}

	if ($handle === 'jquery') {
		$add_jquery_fallback = true;
	}
	return $src;
}

if (!is_admin()) {
	add_filter('script_loader_src', 'fin_jquery_local_fallback', 10, 2);
}

/**
 * Add Google Analytics to head
 *
 */
function fin_analytics() {
	if(!current_user_can('edit_posts')) {
		$options = get_option('fin_theme_options');
		$analyticsNumber = $options['analytics'];
		if($analyticsNumber != '' && $analyticsNumber != 'UA-#######-#') {
			echo "<script type='text/javascript'>
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', '$analyticsNumber']);
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
add_action('wp_head', 'fin_analytics');