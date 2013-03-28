<?php 
/**
 * Get_Avatar
 * returns '' if no avatar.
 *
 */
function fin_get_avatar($id_or_email, $size='64', $placeholder='404') {
	// get $email from input. Could be $comment, $email, or $user_id
	$authorURL = '';
	$authorName = '';
	if(is_numeric($id_or_email)) {
		$id = (int) $id_or_email;
		$user = get_userdata($id);
		if ( $user ) {
			$email = $user->user_email;
		}
	}elseif(is_object($id_or_email)) {
		if(!empty($id_or_email->user_id)) {
			$id = (int) $id_or_email->user_id;
			$user = get_userdata($id);
			if ( $user ) {
				$email = $user->user_email;
				$authorName = $user->display_name;
				$authorURL = get_edit_profile_url($id);
			}
		}elseif(!empty($id_or_email->comment_author_email)) {
			$email = $id_or_email->comment_author_email;
			$authorName = $id_or_email->comment_author;
			$authorURL = $id_or_email->comment_author_url;
		}
	}else {
		$email = $id_or_email;
	}
	
	// Create gravatar url using placeholder or 404
	if($placeholder != '404') {
		$placeholder = urlencode($placeholder);
	}
	$image = 'http://www.gravatar.com/avatar/' . md5($email) . '?s=' . $size . '&d=' . $placeholder;
	$headers = get_headers($image);
	if (!strpos($headers[0],'200')) {	// no avatar
		return '';
	}
	
	$avatar = '<img src="' . $image . '" class="avatar" alt="' . $authorName . '">';
	
	
	if($authorURL) {
		return '<a href="' . $authorURL . '" rel="external nofollow">' . $avatar . '</a>';
	}else { // no URL
		return $avatar;
	}
}

/**
 * Default Comment Structure
 *
 */
class Fin_Walker_Comment extends Walker_Comment {
	function start_lvl(&$output, $depth = 0, $args = array()) {
		$GLOBALS['comment_depth'] = $depth + 1; ?>
		<ol <?php comment_class(); ?>>
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
			 <?php include(locate_template('/templates/content/comment.php'));
		//</li> added below
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
		
		'logged_in_as' => '<ul class="button-group">
			<li><a class="button small" href="' . admin_url( "profile.php" ) . '"><i class="icon-user"></i> ' . $user_identity . '</a></li>
			<li><a class="button small alert" href="' . wp_logout_url( apply_filters( "the_permalink", get_permalink( ) ) ) . '"><i class="icon-ban-circle"></i> Log Out</a></li>
		</ul>',
		
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