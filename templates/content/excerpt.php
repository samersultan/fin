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
	<div class="entry-summary">
		<?php the_excerpt(); ?>
	</div>
	<footer>
		<ul>
			<li class="meta_time"><time class="published" datetime="<?php the_time('c'); ?>"><span class="month"><?php the_time('F'); ?></span> <span class="day"><?php the_time('j'); ?></span><span class="suffix"><?php the_time('S'); ?></span> <span class="year"><?php the_time('Y'); ?></span></time></li>
			<?php if(count(get_the_category())) { ?>
				<li class="meta_categories"><span>Categories: </span><?php echo get_the_category_list(', '); ?></li>
			<?php } ?>
			<?php $tags_list = get_the_tag_list('',' ');
			if($tags_list){ ?>
				<li class="meta_tags"><span>Tags: </span><?php echo $tags_list; ?></li>
			<?php } ?>
			<?php $comment_count = get_comment_count($post->ID);
				if ( comments_open() && $comment_count['approved'] > 0 ){ ?>
				<li class="meta_comments"><?php comments_popup_link(__(''),__('1 Comment'),__('% Comments')); ?></li>
			<?php } ?>
			<?php edit_post_link('edit', '<li class="meta_edit">', '</li>'); ?>
		</ul>
	</footer>
</article>