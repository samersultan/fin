<article <?php post_class('excerpt'); ?>>
	<h3 class="entry-title"><a href="<?php the_permalink(); ?>" rel="bookmark" title="Bookmark for <?php the_title_attribute(); ?>" class="bookmark"><?php the_title(); ?></a></h3>
	<?php //Thumbnail
	if(has_post_thumbnail($post->ID)) { ?>
		<figure class="entry-thumbnail">
			<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
				<?php echo get_the_post_thumbnail($post->ID,'thumbnail'); ?>
			</a>
		</figure>
	<?php } ?>
	<div class="entry-summary row">
		<?php the_excerpt(); ?>
	</div>
	<footer>
			<?php get_template_part('templates/content/meta'); ?>
	</footer>
</article>