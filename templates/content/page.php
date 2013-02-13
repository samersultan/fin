<article <?php post_class('single span12'); ?>>
	<?php if(!is_front_page()) { ?>
		<h3 class="entry-title"><a href="<?php the_permalink(); ?>" rel="bookmark" title="Bookmark for <?php the_title_attribute(); ?>" class="bookmark"><?php the_title(); ?></a></h3>
	<?php } ?>
	<?php //Thumbnail
	if(has_post_thumbnail($post->ID)) { ?>
		<figure class="entry-thumbnail">
			<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
				<?php echo get_the_post_thumbnail($post->ID,'medium'); ?>
			</a>
		</figure>
	<?php } ?>
	<div class="entry-content row">
		<?php the_content(); ?>
	</div>
	<?php edit_post_link('<i class="icon-pencil"></i> edit'); ?>
</article>