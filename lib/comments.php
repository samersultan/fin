<?php 
/**
 * Replace Avatar HTML
 *
 */
function fin_get_avatar($avatar) {
	$avatar = str_replace("class='avatar", "class='avatar pull-left media-object", $avatar);
	return $avatar;
}

/**
 * Default Comment Structure
 *
 */
class Fin_Walker_Comment extends Walker_Comment {
	function start_lvl(&$output, $depth = 0, $args = array()) {
		$GLOBALS['comment_depth'] = $depth + 1; ?>
		<ol <?php comment_class('media unstyled comment-' . get_comment_ID()); ?>>
	<?php }
	
	function end_lvl(&$output, $depth = 0, $args = array()) {
		$GLOBALS['comment_depth'] = $depth + 1;
		echo '</ol>';
	}
	
	function start_el(&$output, $comment, $depth, $args, $id = 0) {
		$depth++;
		$GLOBALS['comment_depth'] = $depth;
		$GLOBALS['comment'] = $comment;
		
		if(!empty($args['callback'])) {
			call_user_func($args['callback'], $comment, $args, $depth);
			return;
		}
		
		extract($args, EXTR_SKIP); ?>
		
		<li <?php comment_class('media comment-' . get_comment_ID()); ?>>
			<div class="media-object pull-left">
				<?php echo get_avatar($comment, $size = '64');
				comment_reply_link(array_merge($args, array('reply_text' => '<i class="icon-comments"></i> reply', 'depth' => $depth, 'max_depth' => $args['max_depth']))); ?>
			</div>
			<section class="media-body">
				<header class="comment-author vcard">
				  <?php printf(__('<cite class="fn">%s</cite>', 'fin'), get_comment_author_link()); ?>
				  <time class="alignright" datetime="<?php echo comment_date('c'); ?>"><a href="<?php echo htmlspecialchars(get_comment_link($comment->comment_ID)); ?>"><?php echo get_time_ago(get_comment_time('U')); ?></a></time>
				</header>
				<?php if ($comment->comment_approved == '0') { ?>
					<div class="alert alert-info fade in">
						<a class="close" data-dismiss="alert">&times;</a>
						<i class="icon-magic"></i> <?php _e('Awaiting Moderation.', 'fin'); ?>
					</div>
				<?php }
				echo get_comment_text();
				edit_comment_link('<i class="icon-pencil"></i> ' . __('edit', 'fin'), '', ''); ?>
			</section>
		<?php //</li> added below
	}
		
	function end_el(&$output, $comment, $depth = 0, $args = array()) {
		if (!empty($args['end-callback'])) {
			call_user_func($args['end-callback'], $comment, $args, $depth);
			return;
		}
		echo "</li>\n";
	}
}

/**
 * Default Comment Form
 *
 */
function fin_change_comment_form($arg) {
	global $user_identity;
	$commenter = wp_get_current_commenter();
	
	$req = get_option( 'require_name_email' );
	
	$emailReg = " pattern='"."^([0-9a-zA-Z]([-\.\w]*[0-9a-zA-Z])*@([0-9a-zA-Z][-\w]*[0-9a-zA-Z]\.)+[a-zA-Z]{2,9})$"."'";
	$urlReg = "";
	
	$authorLabel = __( 'Name' ) .( $req ? ' *' : '' );
	$emailLabel = __( 'Email' ) .( $req ? ' *' : '' );
	$urlLabel = __( 'Website' );
	
	$fields = array(
		'author' => '<label for="author" class="hide">' . $authorLabel . '</label>
		<div class="span12 input-group">
			<a href="#author" class="input-group-addon" data-toggle="tooltip" title="' . $authorLabel . '"><i class="icon-user"></i></a>
			<input id="author" name="author" type="text" value="' .esc_attr( $commenter['comment_author'] ) . '" placeholder="' . $authorLabel . '" tabindex="1"' . ($req ? ' required ':'') . '>
		</div><br>',
		
		'email'  => '<label for="email" class="hide">' . $emailLabel . '</label>
		<div class="span12 input-group">
			<a href="#email" class="input-group-addon" data-toggle="tooltip" title="' . $emailLabel . '"><i class="icon-envelope"></i></a>
			<input id="email" name="email" type="email"'.$emailReg.'" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" placeholder="' . $emailLabel .'" tabindex="2"' . ($req ? ' required ':'') . '>
		</div><br>',
		
		'url'    => '<label for="url" class="hide">' . $urlLabel .'</label>
		<div class="span12 input-group">
			<a href="#url" class="input-group-addon" data-toggle="tooltip" title="' . $urlLabel . '"><i class="icon-home"></i></a>
			<input id="url" name="url" type="url"'.$urlReg.'" value="' . esc_attr( $commenter['comment_author_url'] ) . '" placeholder="' . $urlLabel .'" tabindex="3">
		</div><br>'
		);

	$arg = array(
		'fields' => apply_filters('comment_form_default_fields', $fields),
	
	    'comment_field' => '<div class="span12 input-group"><label for="comment" class="hide">' . __( 'Comment' ) . '<span class="required"> *</span></label><textarea id="comment" name="comment" cols="45" rows="9" placeholder="' . __( 'Your Comment (required)' ) .'" tabindex="4" required></textarea></div><br>',
	                
	    'must_log_in' => sprintf( __( 'You must be <a class="btn btn-primary" href="%s">logged in</a> to post a comment.'), wp_login_url( apply_filters( 'the_permalink', get_permalink() ) ) ),
		
		'logged_in_as' => sprintf( __( '<span class="alert alert-success logged-in-as">Logged in as: <div class="btn-group alignright"><a class="btn btn-small" href="%s"><i class="icon-user"></i> %s</a><a href="%s" title="Log out of this account" class="btn btn-warning btn-small">Log out?</a></div></span>' ), admin_url( 'profile.php' ), $user_identity, wp_logout_url( apply_filters( 'the_permalink', get_permalink( ) ) ) ),
		
		'comment_notes_before' => '',
		
		'comment_notes_after' => '',
	    
	    'id_form' => 'commentform',
	    
	    'id_submit' => 'submit',
	
	    'title_reply' => __( 'Leave a comment' ),
	    
	    'title_reply_to' => __( 'Replying to %s' ),
	    
	    'cancel_reply_link' => __( 'Cancel reply' ),
	    
	    'label_submit' => __( 'Add Comment' ),
	);
	
	return $arg;
}
add_filter('comment_form_defaults', 'fin_change_comment_form');