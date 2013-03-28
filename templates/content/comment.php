<?php // get avatar
$avatar = fin_get_avatar($comment, $size = '64');
if($avatar) { ?>
	<figure class="avatar">
			<?php echo $avatar; ?>
	</figure>
<?php } ?>
<header>
	<cite class="fn"><?php echo get_comment_author_link(); ?></cite>
</header>
<section>
	<?php if ($comment->comment_approved == '0') { ?>
		<div data-alert class="alert-box secondary">
			<a href="#" class="close">&times;</a>
			<i class="icon-magic"></i> <?php _e('Awaiting Moderation.', 'fin'); ?>
		</div>
	<?php }
	echo get_comment_text(); ?>
</section>
<footer>
	<time datetime="<?php echo comment_date('c'); ?>"><a href="<?php echo htmlspecialchars(get_comment_link($comment->comment_ID)); ?>"><?php echo get_time_ago(get_comment_time('U')); ?></a></time>
	<?php edit_comment_link('<i class="icon-pencil"></i> ' . __('edit', 'fin'), '', '');
	comment_reply_link(array_merge($args, array('reply_text' => '<i class="icon-comments"></i> reply', 'depth' => $depth, 'max_depth' => $args['max_depth'])), $comment->comment_ID); ?>
</footer>