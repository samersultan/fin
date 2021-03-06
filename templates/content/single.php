<article <?php post_class('single'); ?>>
	<header>
		<h3 class="entry-title"><a href="<?php the_permalink(); ?>" rel="bookmark" title="Bookmark for <?php the_title_attribute(); ?>" class="bookmark"><?php the_title(); ?></a></h3>
	</header>
	<section class="entry-content row">
		<?php the_content(); ?>
	</section>
	<footer>
		<?php get_template_part('templates/content/meta');
		wp_link_pages(array('before' => '<nav class="page-nav">' . __('Pages:', 'fin'), 'after' => '</nav>')); ?>
	</footer>
</article>