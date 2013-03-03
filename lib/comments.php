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
		
		<li <?php comment_class('comment-' . get_comment_ID()); ?>>
			<header class="small-2 columns small-offset-<?php echo ($depth * 2) - 2; ?>">
				<figure><?php echo get_avatar($comment, $size = '64'); ?></figure>
				<?php printf(__('<cite class="fn">%s</cite>', 'fin'), get_comment_author_link()); ?>
			</header>
			<section class="columns small-<?php echo (12 - $depth * 2); ?>">
				<?php if ($comment->comment_approved == '0') { ?>
					<div data-alert class="alert-box secondary">
						<a href="#" class="close">&times;</a>
						<i class="icon-magic"></i> <?php _e('Awaiting Moderation.', 'fin'); ?>
					</div>
				<?php }
				echo get_comment_text(); ?>
			</section>
			<footer class="columns small-<?php echo (12 - $depth * 2); ?>">
				<time datetime="<?php echo comment_date('c'); ?>"><a href="<?php echo htmlspecialchars(get_comment_link($comment->comment_ID)); ?>"><?php echo get_time_ago(get_comment_time('U')); ?></a></time>
				<?php edit_comment_link('<i class="icon-pencil"></i> ' . __('edit', 'fin'), '', '');
				comment_reply_link(array_merge($args, array('reply_text' => '<i class="icon-comments"></i> reply', 'depth' => $depth, 'max_depth' => $args['max_depth']))); ?>
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

/**
 * Add a spam-trap to comment-form
 *
 * Include a hidden field called name and set it to hidden. If it receives an input, we have a bot!
 */
function fin_add_spam_trap($arg) {
	$decoyFields = array( 'firstname', 'lastname', 'email2', 'address', 'address2', 'city', 'state', 'zipcode', 'telephone', 'phone');
	$arg['fields'] = array_reverse($arg['fields'], true); //reverse order to place decoys at front of form.
	
	// Get unique daily ID
	srand(date('Ymd'));
	$number = rand(0,9999999);
	$hash = substr(sha1($number),0,8);
	
	$spamtrap = '';
	foreach ($decoyFields as $decoy) {
		$spamtrap .= '<label for="' . $decoy . '" class="hide">' . $decoy . ' *</label><input name="name" id="' . $decoy . $hash . '" type="text" class="hide">';
	}
	$arg['fields']['spamtrap'] = $spamtrap;
	$arg['fields'] = array_reverse($arg['fields'], true); //reverse back so fields are in regular order
	
	// Add hashes to author and email
	$arg['fields']['author'] = str_replace('name="author"', 'name="author' . $hash . '"', $arg['fields']['author'] );
	$arg['fields']['email'] = str_replace('name="email"', 'name="email' . $hash . '"', $arg['fields']['email'] );
	return $arg;
}
add_filter('comment_form_defaults', 'fin_add_spam_trap');

function fin_fix_hashed_comment($commentdata) {
	// Get unique daily ID
	srand(date('Ymd'));
	$number = rand(0,9999999);
	$hash = substr(sha1($number),0,8);
	
	// fix hashed author & email fields
	if(isset($_POST['author' . $hash])) {
		$_POST['author'] = trim(strip_tags($_POST['author' . $hash]));
	}
	if(isset($_POST['email' . $hash])) {
		$_POST['email'] = trim(strip_tags($_POST['email' . $hash]));
	}
	return $commentdata;
}
add_action('pre_comment_on_post', 'fin_fix_hashed_comment');

function fin_check_spamtrap($comment_id, $approved) {
	if($approved != 'spam') { // No need to check twice
		$decoyFields = array( 'firstname', 'lastname', 'email2', 'address', 'address2', 'city', 'state', 'zipcode', 'telephone', 'phone');
		
		// Get unique daily ID
		srand(date('Ymd'));
		$number = rand(0,9999999);
		$hash = substr(sha1($number),0,8);
		
		foreach ($decoyFields as $decoy) {
			if(isset($_POST[$decoy . $hash])) {
				wp_spam_comment($comment_id);
			}
		}
	}
}
add_action('comment_post', 'fin_check_spamtrap');