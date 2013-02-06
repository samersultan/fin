<?php
/**
 * Basic Config and Constants Setup After Installation
 *
 */
function fin_init() {
	//Change Default Tagline
	if(get_bloginfo('description') == 'Just another WordPress site') {
		update_option('blogdescription','');
	}
	
	//Keep wordpress from reformatting posts
	//remove_filter('the_content', 'wpautop');
	
	// Add post thumbnails (http://codex.wordpress.org/Post_Thumbnails)
	add_theme_support('post-thumbnails');
	
	// Add post formats (http://codex.wordpress.org/Post_Formats)
	add_theme_support('post-formats', array('gallery', 'image', 'video', 'audio'));
	
	// Allow shortcodes in widgets
	add_filter( 'widget_text', 'shortcode_unautop');
	add_filter( 'widget_text', 'do_shortcode', 11);
	
	// Change Uploads folder to /Assets
	update_option('uploads_use_yearmonth_folders', 0);
	update_option('upload_path', 'assets');
	
	// Rewrite Permalink Structure
	update_option('category_base', '/site/');
	update_option('permalink_structure', '/%category%/%postname%/');
	
	// change start of week to Sunday
	update_option('start_of_week',0);
	
	// Disable Smilies
	update_option('use_smilies', 0);
	
	// Default Comment Status
	update_option( 'default_comment_status', 'closed' );
	update_option( 'default_ping_status', 'closed' );
	
	// Set the size of the Post Editor
	update_option('default_post_edit_rows', 60);
	
	// Set the post revisions to 5 unless previously set to avoid DB bloat
	if (!defined('WP_POST_REVISIONS')) { define('WP_POST_REVISIONS', 3); }
	
	// Set Timezone
	//$timezone = "America/New_York";
	$timezone = "America/Chicago";
	//$timezone = "America/Denver";
	//$timezone = "America/Los_Angeles";
	update_option('timezone_string',$timezone);
}
add_action('after_switch_theme', 'fin_init');

/**
 * Basic Config and Constants Called Every Load
 *
 */
function fin_setup() {
	// Add post thumbnails (http://codex.wordpress.org/Post_Thumbnails)
	add_theme_support('post-thumbnails');
}
add_action('after_setup_theme', 'fin_setup');
/**
 * Create default content for new posts and pages by pulling from /pages/page-default
 *
 */
function fin_add_default_content($content) {
	$content = file_get_contents(locate_template('/lib/pages/page-default.php'));
	return $content;
}
add_filter('default_content', 'fin_add_default_content');
/**
 * Change text for password protected areas
 *
 */
function fin_change_password_text($content) {
	$content = str_replace(
		'This post is password protected. To view it please enter your password below:', 
		'This area is password protected. To view it please enter your password below:',
		$content);
	return $content;
}
add_filter('the_content','fin_change_password_text');

/**
 * Set media sizes based on column and lineheight variables
 *
 */
if (!isset($content_width)) { $content_width = 1000; }
function fin_media_size() {
	$lineHeight = 20;
	$columnWidth = 83;
	$gutterWidth = 30;
	
	$thumb_w = ($columnWidth * 2) + ($gutterWidth * (2 - 1));
	$medium_w = ($columnWidth * 4) + ($gutterWidth * (4 - 1));
	$large_w = ($columnWidth * 8) + ($gutterWidth * (8 - 1));
	$xLarge_w = ($columnWidth * 12) + ($gutterWidth * (12 - 1));
	$embed_w = $xLarge_w;
	
	$thumb_h = $thumb_w;
	$medium_h = ceil(($medium_w * 2/3) / $lineHeight) * $lineHeight;
	$large_h = ceil(($large_w * 2/3) / $lineHeight) * $lineHeight;
	$xLarge_h = ceil(($xLarge_w * 2/3) / $lineHeight) * $lineHeight;
	$embed_h = $xLarge_h;
	
	add_image_size('xLarge', $xLarge_w, $xLarge_h);
	
	$sizes = array(
		array( 'name' => 'thumbnail_size_w', 'value' => $thumb_w ),
		array( 'name' => 'thumbnail_size_h', 'value' => $thumb_h ),
		array( 'name' => 'medium_size_w', 'value' => $medium_w ),
		array( 'name' => 'medium_size_h', 'value' => $medium_h ),
		array( 'name' => 'large_size_w', 'value' => $large_w ),
		array( 'name' => 'large_size_h', 'value' => $large_h ),
		array( 'name' => 'embed_size_w', 'value' => $embed_w ),
		array( 'name' => 'embed_size_h', 'value' => $embed_h )
	);
	foreach ( $sizes as $size ) {
		if ( get_option( $size['name'] ) != $size['value'] ) {
			update_option( $size['name'], $size['value'] );
		} else {
			$deprecated = ' ';
			$autoload = 'no';
			add_option( $size['name'], $size['value'] );
		}
	}
}
add_action('after_setup_theme', 'fin_media_size');

/**
 * Change the_category and get_the_category_list
 *
 */
function fin_the_category($list) {
	$list = str_replace('rel="category tag">', 'rel="category tag" class="meta_category btn btn-small btn-info">', $list);
	return $list;
}
add_filter('the_category', 'fin_the_category');

/**
 * Change the_tags and get_tag_list
 *
 */
function fin_the_tags($list) {
	$list = str_replace('rel="tag">', 'rel="tag" class="meta_tag btn btn-small btn-info">', $list);
	return $list;
}
add_filter('the_tags', 'fin_the_tags');

/**
 * Default Comment Structure
 *
 */
function fin_comment($comment, $args, $depth) {
  $GLOBALS['comment'] = $comment; ?>
  <li <?php comment_class('media'); ?>>
  	<?php if ($comment->comment_approved == '0') { ?>
  	  <div class="alert alert-info">
  	    <button type="button" class="close" data-dismiss="alert">&times;</button>
  	    <i class="icon-magic"></i> <?php _e('Your comment is awaiting moderation.', 'fin'); ?>
  	  </div>
  	<?php } ?>
  	<div class="media-object pull-left">
			<?php echo get_avatar($comment, $size = '64');
			comment_reply_link(array_merge($args, array('depth' => $depth, 'max_depth' => $args['max_depth'], 'reply_text' => '<i class="icon-comments"></i> reply')));
			edit_comment_link('<i class="icon-pencil"></i> ' . __('edit', 'fin'), '', ''); ?>
		</div>
    <section class="comment media-body">
  		<header class="comment-author vcard">
  		  <?php printf(__('<cite class="fn">%s</cite>', 'fin'), get_comment_author_link()); ?>
  		  <time datetime="<?php echo comment_date('c'); ?>"><a href="<?php echo htmlspecialchars(get_comment_link($comment->comment_ID)); ?>"><?php echo get_time_ago(get_comment_time('U')); ?></a></time>
  		</header>
      <?php comment_text(); ?>
    </section>
<?php // "</li>" gets added automatically by WordPress
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
	
	$fields = array(
		'author' => '<label for="author" class="hide">' . __( 'Name' ) .( $req ? '<span class="required"> *</span>' : '' ) . '</label>
		<div class="input-group span6">
			<span class="input-group-addon"><i class="icon-user"></i></span>
			<input id="author" name="author" type="text" value="' .esc_attr( $commenter['comment_author'] ) . '" placeholder="'. __( 'Name' ) . ($req ? ' (required)':'') . '" tabindex="1"' . ($req ? ' required ':'') . '>
		</div><br>',
		
		'email'  => '<label for="email" class="hide">' . __( 'Email' ) .( $req ? '<span class="required"> *</span>' : '' ) . '</label>
		<div class="input-group span6">
			<span class="input-group-addon"><i class="icon-envelope"></i></span>
			<input id="email" name="email" type="email"'.$emailReg.'" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" placeholder="' . __( 'Email' ) . ($req ? ' (required)':'') .'" tabindex="2"' . ($req ? ' required ':'') . '>
		</div><br>',
		
		'url'    => '<label for="url" class="hide">' . __( 'Website' ) .'</label>
		<div class="input-group span6">
			<span class="input-group-addon"><i class="icon-home"></i></span>
			<input id="url" name="url" type="url"'.$urlReg.'" value="' . esc_attr( $commenter['comment_author_url'] ) . '" placeholder="' . __( 'Website (optional)' ) .'" tabindex="3">
		</div><br>'
		);

	$arg = array(
		'fields' => apply_filters('comment_form_default_fields', $fields),
	
	    'comment_field' => '<div class="input-group span6"><label for="comment" class="hide">' . __( 'Comment' ) . '<span class="required"> *</span></label><textarea id="comment" name="comment" cols="45" rows="9" placeholder="' . __( 'Your Comment (required)' ) .'" tabindex="4" required></textarea></div><br>',
	                
	    'must_log_in' => sprintf( __( 'You must be <a class="btn btn-primary" href="%s">logged in</a> to post a comment.'), wp_login_url( apply_filters( 'the_permalink', get_permalink() ) ) ),
		
		'logged_in_as' => sprintf( __( '<p>Logged in as: <div class="btn-group"><a class="btn btn-small" href="%s"><i class="icon-user"></i> %s</a><a href="%s" title="Log out of this account" class="btn btn-warning btn-small">Log out?</a></div></p>' ), admin_url( 'profile.php' ), $user_identity, wp_logout_url( apply_filters( 'the_permalink', get_permalink( ) ) ) ),
		
		'comment_notes_before' => '',
		
		'comment_notes_after' => '',
	    
	    'id_form' => 'commentform',
	    
	    'id_submit' => 'submit',
	
	    'title_reply' => __( 'Leave a comment' ),
	    
	    'title_reply_to' => __( 'Leave a reply to %s' ),
	    
	    'cancel_reply_link' => __( 'Cancel reply' ),
	    
	    'label_submit' => __( 'Add Comment' ),
	);
	
	return $arg;
}
add_filter('comment_form_defaults', 'fin_change_comment_form');