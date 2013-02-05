<span class="meta_time btn btn-small btn-info disabled"><i class="icon-time"></i> <time class="published" datetime="<?php the_time('c'); ?>"><span class="month"><?php the_time('F'); ?></span> <span class="day"><?php the_time('j'); ?></span><sup class="suffix"><?php the_time('S'); ?></sup>, <span class="year"><?php the_time('Y'); ?></span></time></span>
<?php 
// Comments
$comment_count = get_comment_count($post->ID);
	if ( comments_open() && $comment_count['approved'] > 0 ){ ?>
	<span class="meta_comments btn-group"><?php comments_popup_link('','<i class="icon-comment"></i> ' . __('1 Comment'), '<i class="icon-comments"></i> ' . __('% Comments'), 'btn btn-info btn-small'); ?></span>
<?php }
// Gallery image count
$gallery = get_image_count($id);
if($gallery > 0) { ?>
	<span class="btn btn-small btn-info"><i class="icon-camera-retro"></i> <?php echo $gallery; ?></span>
<?php }
// Exif Information
$exif = get_exif($id, '</span><span class="btn btn-small btn-info">', '<span class="btn btn-small btn-info">', '</span>');
if($exif != '') { ?>
	<span class="meta_exif btn-group"><span class="btn btn-small btn-info disabled"><i class="icon-camera"></i></span><?php echo $exif; ?></span>
<?php }
// Categories
if(count(get_the_category())) { ?>
	<span class="meta_categories btn-group"><span class="btn btn-small btn-info disabled"><i class="icon-folder-close"></i></span><?php echo get_the_category_list(' '); ?></span>
<?php }
// Tags
$tags_list = get_the_tag_list('','');
if($tags_list){ ?>
	<span class="meta_tags btn-group"><span class="btn btn-small btn-info disabled"><i class="icon-tags"></i></span><?php echo $tags_list; ?></span>
<?php }
// Edit
edit_post_link('<i class="icon-pencil"></i> edit', '<span class="meta_edit btn-group">', '</span>'); ?>