<footer id="footer" role="contentinfo">
	<?php $options = get_option('fin_theme_options');
	if($options['social'] != '') { ?>
		<div class="social">
			<a href="<?php echo esc_url(get_permalink(get_page_by_title('Contact'))); ?>"><i class="icon-phone-sign icon-2x"></i></a>
			<?php // Facebook
			if($options['social_facebook'] != '') { ?>
				<a class="facebook" href="<?php echo 'http://' . preg_replace('`^http://`is', '', $options['social_facebook']); ?>"><i class="icon-facebook-sign icon-2x"></i></a>
			<?php }  
			// Twitter
			if($options['social_twitter'] != '') { ?>
				<a class="twitter" href="<?php echo 'http://' . preg_replace('`^http://`is', '', $options['social_twitter']); ?>"><i class="icon-twitter-sign icon-2x"></i></a>
			<?php }  // Google+
			if($options['social_google_plus'] != '') { ?>
				<a href="<?php echo 'http://' . preg_replace('`^http://`is', '', $options['social_google_plus']); ?>"><i class="icon-google-plus-sign icon-2x"></i></a>
			<?php } 
			// Pinterest
			if($options['social_pinterest'] != '') { ?>
				<a class="pinterest" href="<?php echo 'http://' . preg_replace('`^http://`is', '', $options['social_pinterest']); ?>"><i class="icon-pinterest-sign icon-2x"></i></a>
			<?php }  // Linkedin
			if($options['social_linkedin'] != '') { ?>
				<a class="linkedin" href="<?php echo 'http://' . preg_replace('`^http://`is', '', $options['social_linkedin']); ?>"><i class="icon-linkedin-sign icon-2x"></i></a>
			<?php }  // Github
			if($options['social_github'] != '') { ?>
				<a class="github" href="<?php echo 'http://' . preg_replace('`^http://`is', '', $options['social_github']); ?>"><i class="icon-github-sign icon-2x"></i></a>
			<?php }else {
				$themeData = wp_get_theme();
				$developerURL = $themeData->{'Author URI'};
				if($developerURL != ''){ ?>
					<a class="developer" href="<?php echo $developerURL ?>"><i class="icon-desktop icon-2x"></i></a>
				<?php }
			} ?>
		</div>
	<?php } ?>
	<?php if (has_nav_menu('secondary_menu')) {
		wp_nav_menu(array('theme_location' => 'secondary_menu', 'menu_class' => 'inline-list'));
	}else { ?>
		<div class="copyrights">
			<a href="<?php echo esc_url(get_permalink(get_page_by_title( 'Copyrights'))); ?>">&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?></a>
		</div>
	<?php } ?>
</footer>