<article <?php post_class('excerpt'); ?>>
	<header>
		<h3 class="entry-title"><a href="<?php the_permalink(); ?>" rel="bookmark" title="Bookmark for <?php the_title_attribute(); ?>" class="bookmark"><?php the_title(); ?></a></h3>
	</header>
	<section class="entry-summary row">
		<?php //Thumbnail
		if(has_post_thumbnail($post->ID) && ! post_password_required()) { ?>
			<figure class="entry-thumbnail">
				<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
					<?php echo get_the_post_thumbnail($post->ID,'thumbnail'); ?>
				</a>
			</figure>
		<?php } ?>
		<?php the_excerpt(); ?>
	</section>
	<footer>
		<?php get_template_part('templates/content/meta'); ?>
	</footer>
</article>