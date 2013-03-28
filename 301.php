<?php get_template_part('templates/head'); ?>
<body id="301">
	<div id="wrap" role="document">
		<?php do_action('get_header'); ?>
		<header id="header" role="banner" <?php if(!is_front_page()) { echo 'class="hidden-phone"'; } ?>>
			<hgroup>
				<?php $options = get_option('fin_theme_options');
				$logo = $options['logo'];
				if($logo) { ?>
					<figure class="logo" role="logo"><a href="<?php echo home_url(); ?>/"><img src="<?php echo $logo; ?>"></a></figure>
				<?php } ?>
				<h1 class="brand"><a href="<?php echo home_url(); ?>/"><?php bloginfo('name'); ?></a></h1>
				<?php if(get_bloginfo('description') != '') { ?>
					<h2 class="description"><a href="<?php echo esc_url(get_permalink(get_page_by_path( 'about'))); ?>"><?php echo get_bloginfo('description'); ?></a></h2>
				<?php } ?>
			</hgroup>
		</header>
		<main id="main" role="main">
			<?php $page = get_page_by_path('maintenance');
			if($page) {
				$content = apply_filters('the_content', $page->post_content);
				echo $content;
			}else {
				echo '<h1>Under Construction</h1><h4>Please check back soon</h4>';
			} ?>
		</main>
	</div>
	<div id="footer">
		<footer id="main-info" role="contentinfo" class="row">
			<p class="pull-left"><a href="<?php echo esc_url(get_permalink(get_page_by_title( 'Copyrights'))); ?>">&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?></a></p>
			<?php /* Developer Info */
			$themeData = wp_get_theme();
			$developerName =$themeData->Author;
			if($developerName != ''){
				echo '<p class="pull-right">Built by: ' . $developerName . '</p>';
			} ?>
			<?php $options = get_option('fin_theme_options');
			if($options['social'] != '') { ?>
				<div class="social six columns">
					<a href="<?php echo esc_url(get_permalink(get_page_by_title('Contact'))); ?>"<i class="icon-phone-sign icon-2x"></i></a>
					<?php // Facebook
					if($options['social_facebook'] != '') { ?>
						<a href="<?php echo 'http://' . preg_replace('`^http://`is', '', $options['social_facebook']); ?>"><i class="icon-facebook-sign icon-2x"></i></a>
					<?php } ?>
					<?php // Twitter
					if($options['social_twitter'] != '') { ?>
						<a href="<?php echo 'http://' . preg_replace('`^http://`is', '', $options['social_twitter']); ?>"><i class="icon-twitter-sign icon-2x"></i></a>
					<?php } ?>
					<?php // Google+
					if($options['social_google_plus'] != '') { ?>
						<a href="<?php echo 'http://' . preg_replace('`^http://`is', '', $options['social_google_plus']); ?>"><i class="icon-google-plus-sign icon-2x"></i></a>
					<?php } ?>
					<?php // Pinterest
					if($options['social_pinterest'] != '') { ?>
						<a href="<?php echo 'http://' . preg_replace('`^http://`is', '', $options['social_pinterest']); ?>"><i class="icon-pinterest-sign icon-2x"></i></a>
					<?php } ?>
					<?php // Linkedin
					if($options['social_linkedin'] != '') { ?>
						<a href="<?php echo 'http://' . preg_replace('`^http://`is', '', $options['social_linkedin']); ?>"><i class="icon-linkedin-sign icon-2x"></i></a>
					<?php } ?>
					<?php // Linkedin
					if($options['social_github'] != '') { ?>
						<a href="<?php echo 'http://' . preg_replace('`^http://`is', '', $options['social_github']); ?>"><i class="icon-github-sign icon-2x"></i></a>
					<?php } ?>
				</div>
			<?php } ?>
		</footer>
	</div>
</body>
</html>