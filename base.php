<?php $options = get_option('fin_theme_options');
if($options['construction'] && !is_admin() && !is_admin_bar_showing()) {
	get_template_part('301');
}else {
	get_template_part('templates/head'); ?>
	<body <?php body_class(); ?>>
		<!--[if lt IE 9]><div class="alert-error browser-warning">Your browser is out of date. <a href="http://browsehappy.com/">Please upgrade to a modern browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a>.</div><![endif]-->
		<section id="wrap" role="document">
			<?php get_template_part('templates/header'); ?>
			<main id="main" class="container" role="main">
				<div id="content" class="row">
					<?php if(!have_posts()) {
						get_template_part('templates/error','index');
					}else {
						while (have_posts()) : the_post();
							include fin_template_path(); // custom template structure
						endwhile;
					} ?>
				</div>
				<?php get_template_part('templates/pagination');
				if(is_single() && !is_page()) {
					comments_template('/templates/comments.php');
				}
				get_template_part('templates/sidebar'); ?>
			</main>
		</section>
		<?php get_template_part('templates/footer'); ?>
		<?php wp_footer(); ?>
	</body>
	</html>
<?php } ?>