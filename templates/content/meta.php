<span class="meta_time btn btn-small btn-info disabled"><i class="icon-time"></i> <time class="published" datetime="<?php the_time('c'); ?>"><span class="month"><?php the_time('F'); ?></span> <span class="day"><?php the_time('j'); ?></span><sup class="suffix"><?php the_time('S'); ?></sup>, <span class="year"><?php the_time('Y'); ?></span></time></span>
<?php $comment_count = get_comment_count($post->ID);
	if ( comments_open() && $comment_count['approved'] > 0 ){ ?>
	<span class="meta_comments btn-group"><?php comments_popup_link('','<i class="icon-comment"></i> ' . __('1 Comment'), '<i class="icon-comments"></i> ' . __('% Comments'), 'btn btn-info btn-small'); ?></span>
<?php }
// If gallery -> get_image_count
// If attachment -> Show exif 
if(count(get_the_category())) { ?>
	<span class="meta_categories btn-group"><span class="btn btn-small btn-info disabled"><i class="icon-folder-close"></i></span><?php echo get_the_category_list(' '); ?></span>
<?php }
$tags_list = get_the_tag_list('','');
if($tags_list){ ?>
	<span class="meta_tags btn-group"><span class="btn btn-small btn-info disabled"><i class="icon-tags"></i></span><?php echo $tags_list; ?></span>
<?php }
edit_post_link('<i class="icon-pencil"></i>edit', '<span class="meta_edit btn-group">', '</span>'); ?>