<div id="footer">
	<footer id="main-info" role="contentinfo" class="row">
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
				<?php // Github
				if($options['social_github'] != '') { ?>
					<a href="<?php echo 'http://' . preg_replace('`^http://`is', '', $options['social_github']); ?>"><i class="icon-github-sign icon-2x"></i></a>
				<?php }else {
					$themeData = wp_get_theme();
					$developerURL = $themeData->{'Author URI'};
					if($developerURL != ''){ ?>
						<a href="<?php echo $developerURL ?>"><i class="icon-desktop icon-2x"></i></a>
					<?php }
				} ?>
			</div>
		<?php } ?>
		<p class="copyright six columns pull-six"><a href="<?php echo esc_url(get_permalink(get_page_by_title( 'Copyrights'))); ?>">&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?></a></p>
	</footer>
</div>