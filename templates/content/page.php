<article <?php post_class('single twelve columns'); ?>>
	<h3 class="entry-title"><a href="<?php the_permalink(); ?>" rel="bookmark" title="Bookmark for <?php the_title_attribute(); ?>" class="bookmark"><?php the_title(); ?></a></h3>
	<?php //Thumbnail
	if(has_post_thumbnail($post->ID)) { ?>
		<figure class="entry-thumbnail">
			<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
				<?php echo get_the_post_thumbnail($post->ID,'medium'); ?>
			</a>
		</figure>
	<?php } ?>
	<div class="entry-content content">
		<?php the_content(); ?>
	</div>
	<?php edit_post_link('edit', '<span class="meta_edit">', '</span>'); ?>
</article>