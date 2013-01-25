<html>
<head>
	<meta charset="utf-8">
	
	<title><?php bloginfo( 'name' );
	wp_title( '|', true, 'left' );
	if(get_bloginfo('description','Display')){
			echo ' | ' . get_bloginfo('description', 'Display');
	} ?></title>
	
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	
	<?php // Meta Description
	if(get_bloginfo('description','Display')) {
		echo '<meta name="description" content="' . get_bloginfo('description','Display') . '">';
	}else {
	
	} ?>
	<?php // Meta Keywords
	$options = get_option('fin_theme_options');
	$default_keywords = $options['keywords'];
	$tags = '';
	$posttags = get_the_tags();
	if ($posttags) {
		foreach($posttags as $tag) {
			$tags .= $tag->name . ', ';
		}
	}
	if($default_keywords != '' || $tags != '') {
		echo '<meta name="keywords" content="' . $tags . $default_keywords . '">';
	} ?>
	
	<?php wp_head(); ?>
	
</head>
<body id="301">
	<div id="wrap" role="document">
		<header id="header">
			<hgroup>
				<h1 class="brand"><a href="<?php echo home_url(); ?>/"><?php bloginfo('name'); ?></a></h1>
				<?php if(get_bloginfo('description') != '') { ?>
					<h2 class="description"><?php echo get_bloginfo('description'); ?></h2>
				<?php } ?>
			</hgroup>
		</header>
		<section id="main">
			<?php $page = get_page_by_path('maintenance');
			if($page) {
				$content = apply_filters('the_content', $page->post_content);
				echo $content;
			}else {
				echo '<h3>Under Construction</h3><h5>Please check back soon</h5>';
			} ?>
		</section>
		<div id="push"></div>
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
				<div class="social six columns push-six">
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