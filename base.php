<?php get_template_part('templates/head'); ?>
<body <?php body_class(); ?>>
	<!--[if lt IE 9]><div class="alert-error browser-warning">Your browser is out of date. <a href="http://browsehappy.com/">Please upgrade to a modern browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a>.</div><![endif]-->
	<div id="wrap" role="document">
		<?php get_template_part('templates/header'); ?>
			<section id="content">
				<?php if(!have_posts()) {
					get_template_part('templates/error','index');
				} else {
					while (have_posts()) : the_post();
						include fin_template_path(); // custom template structure
					endwhile;
				} ?>
			</section>
		<?php get_template_part('templates/sidebar'); ?>
	</div>
	<?php get_template_part('templates/footer'); ?>
</body>
</html>