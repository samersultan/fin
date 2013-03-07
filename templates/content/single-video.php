<article <?php post_class('single'); ?>>
	<header class="row">
		<h3 class="entry-title"><a href="<?php the_permalink(); ?>" rel="bookmark" title="Bookmark for <?php the_title_attribute(); ?>" class="bookmark"><?php the_title(); ?></a></h3>
	</header>
	<section class="entry-content row">
		<?php echo get_the_video();
		the_content(); ?>
	</section>
	<footer class="row">
		<?php get_template_part('templates/content/meta'); ?>
	</footer>
</article>