<span class="meta_time small secondary button disabled"><i class="icon-time"></i> <time class="published" datetime="<?php the_time('c'); ?>"><?php echo get_time_ago(get_post_time('U')); ?></time></span>
<?php 
// Comments
$comment_count = get_comment_count($post->ID);
	if ( comments_open() && $comment_count['approved'] > 0 ){ ?>
	<?php comments_popup_link('','<i class="icon-comment"></i> ' . __('1 Comment'), '<i class="icon-comments"></i> ' . __('% Comments'), 'small secondary button meta_comments'); ?>
<?php }
// Gallery image count
$gallery = get_image_count($id);
if($gallery > 0) { ?>
	<span class="small secondary button meta_images"><i class="icon-camera-retro"></i> <?php echo $gallery; ?></span>
<?php }
// Exif Information
$exif = get_exif($id, '</span><span class="small secondary button">', '<span class="small secondary button">', '</span>');
if($exif != '') { ?>
	<ul class="meta_exif button-group"><li><a href="#" class="small secondary button disabled"><i class="icon-camera"></i></a></li><?php echo $exif; ?></ul>
<?php }
// Categories
if(count(get_the_category())) { ?>
	<ul class="meta_categories button-group"><li><?php echo get_the_category_list('</li><li>'); ?></li></ul>
<?php }
// Tags
$tags_list = get_the_tag_list('<li>','</li><li>', '</li>');
if($tags_list){ ?>
	<ul class="meta_tags button-group"><?php echo $tags_list; ?></ul>
<?php }
// Edit
edit_post_link('<i class="icon-pencil"></i> edit'); ?>