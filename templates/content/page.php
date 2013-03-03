<article <?php post_class('single'); ?>>
	<?php if(!is_front_page()) { ?>
	<header class="row">
		<h3 class="entry-title"><a href="<?php the_permalink(); ?>" rel="bookmark" title="Bookmark for <?php the_title_attribute(); ?>" class="bookmark"><?php the_title(); ?></a></h3>
	</header>
	<?php } ?>
	<section class="entry-content row">
		<?php the_content(); ?>
	</section class="row">
	<?php edit_post_link('<i class="icon-pencil"></i> edit','<footer>','</footer>'); ?>
</article>