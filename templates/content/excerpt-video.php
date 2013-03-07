<article <?php post_class('excerpt'); ?>>
	<header class="row">
		<h3 class="entry-title"><a href="<?php the_permalink(); ?>" rel="bookmark" title="Bookmark for <?php the_title_attribute(); ?>" class="bookmark"><?php the_title(); ?></a></h3>
	</header>
	<section class="entry-summary">
		<?php //Thumbnail
		if(has_post_thumbnail($post->ID) && ! post_password_required()) { ?>
			<figure class="entry-thumbnail">
				<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
					<?php echo get_the_post_thumbnail($post->ID,'thumbnail'); ?>
				</a>
			</figure>
		<?php }else { ?>
			<figure class="entry-thumbnail">
				<?php echo get_the_video(); ?>
			</figure>
		<?php }
		the_excerpt(); ?>
	</section>
	<footer class="row">
		<?php get_template_part('templates/content/meta'); ?>
	</footer>
</article>