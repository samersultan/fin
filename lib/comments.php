<?php 
/**
 * Default Comment Structure
 *
 */
class Fin_Walker_Comment extends Walker_Comment {
	function start_lvl(&$output, $depth = 0, $args = array()) {
		$GLOBALS['comment_depth'] = $depth + 1; ?>
		<ol <?php comment_class('unstyled'); ?>>
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
		
		<li id="<?php echo 'comment-' . get_comment_ID(); ?>" <?php comment_class(); ?>>
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
		'author' => '<label for="author"><i class="icon-user"></i><span class=""> ' . $authorLabel . '</span></label>
			<input id="author" name="author" type="text" value="' .esc_attr( $commenter['comment_author'] ) . '" placeholder="' . $authorLabel . '" tabindex="1"' . ($req ? ' required ':'') . '>',
		
		'email'  => '<label for="email"><i class="icon-envelope"></i><span class=""> ' . $emailLabel . '</span></label>
			<input id="email" name="email" type="email"'.$emailReg.'" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" placeholder="' . $emailLabel .'" tabindex="2"' . ($req ? ' required ':'') . '>',
		
		'url'    => '<label for="url"><i class="icon-home"></i><span class=""> ' . $urlLabel .'</span></label>
			<input id="url" name="url" type="url"'.$urlReg.'" value="' . esc_attr( $commenter['comment_author_url'] ) . '" placeholder="' . $urlLabel .'" tabindex="3">'
		);

	$arg = array(
		'fields' => apply_filters('comment_form_default_fields', $fields),
	
	    'comment_field' => '<label for="comment"><i class="icon-comment-alt"></i><span class=""> ' . __( 'Comment' ) . '</span></label>
	    <textarea id="comment" name="comment" cols="120" rows="9" placeholder="' . __( 'Your Comment (required)' ) .'" tabindex="4" required></textarea>',
	                
	    'must_log_in' => sprintf( __( 'You must be <a class="button" href="%s">logged in</a> to post a comment.'), wp_login_url( apply_filters( 'the_permalink', get_permalink() ) ) ),
		
		'logged_in_as' => sprintf( __( '<span class="alert alert-success logged-in-as">Logged in as: <a class="button small" href="%s"><i class="icon-user"></i> %s</a><a href="%s" title="Log out of this account" class="button small alert">Log out?</a></span>' ), admin_url( 'profile.php' ), $user_identity, wp_logout_url( apply_filters( 'the_permalink', get_permalink( ) ) ) ),
		
		'comment_notes_before' => '',
		
		'comment_notes_after' => '',
	    
	    'id_form' => 'commentform',
	    
	    'id_submit' => 'submit',
	
	    'title_reply' => __( 'Leave a comment' ),
	    
	    'title_reply_to' => __( 'Replying to %s' ),
	    
	    'cancel_reply_link' => __( '&times;' ),
	    
	    'label_submit' => __( 'Add Comment' ),
	);
	
	return $arg;
}
add_filter('comment_form_defaults', 'fin_change_comment_form');