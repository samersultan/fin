<article <?php post_class('single twelve columns centered'); ?>>
	<?php $parent = $post->post_parent; ?>
	<h3 class="entry-title"><a href="<?php echo get_permalink(); ?>" rel="bookmark" title="Bookmark for <?php echo get_the_title($parent); ?>" class="bookmark"><?php echo get_the_title($parent); ?></a></h3>
	<figure class="entry-thumbnail">
		<a href="<?php echo get_permalink(); ?>"><?php echo wp_get_attachment_image( $post->ID, 'large' ); ?></a>
		<nav class="nav-single">
			<ul>
				<li class="previous older"><?php previous_image_link('','previous'); ?></li>
				<li class="next newer"><?php next_image_link('','next'); ?></li>
			</ul>
		</nav>
	</figure>
	<div class="entry-content">
		<h5><?php the_title(); ?></h5>
		<?php $caption = get_the_excerpt();
		if($caption != '') {
			echo '<h6>' . $caption . '</h6>';
		}
		$description = get_the_content();
		if($description != '') {
			echo '<p>' . $description . '</p>';
		} ?>
	</div>
</article>
<?php //Gallery
$attachments = get_children(array(
	'post_parent'=>$post->post_parent,
	'post_status'=>'inherit',
	'post_type'=>'attachment',
	'post_mime_type'=>'image',
	'orderby'=>'menu_order ID',
	'order' => 'ASC',
	'numberposts' => 999));
if(count($attachments > 1)){ /* More than one image in gallery */ ?>
	<aside class="gallery twelve columns">
		<?php echo do_shortcode( sprintf( '[gallery id="%1$s"]', $post->post_parent) ); ?>
	</aside>
<?php } ?>