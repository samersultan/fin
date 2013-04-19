<?php 
/**
 * Change Review Form Fields
 *
 */
global $woocommerce;
global $user_identity;
$commenter = wp_get_current_commenter();

$req = get_option( 'require_name_email' );

$emailReg = " pattern='"."^([0-9a-zA-Z]([-\.\w]*[0-9a-zA-Z])*@([0-9a-zA-Z][-\w]*[0-9a-zA-Z]\.)+[a-zA-Z]{2,9})$"."'";
$urlReg = "";

$authorLabel = __( 'Name' ) .( $req ? ' *' : '' );
$emailLabel = __( 'Email' ) .( $req ? ' *' : '' );

$fields = array(
	'author' => '<label for="author"><i class="icon-user"></i><span class=""> ' . $authorLabel . '</span></label>
		<input id="author" name="author" type="text" value="' .esc_attr( $commenter['comment_author'] ) . '" placeholder="' . $authorLabel . '" tabindex="1"' . ($req ? ' required ':'') . '>',
	
	'email'  => '<label for="email"><i class="icon-envelope"></i><span class=""> ' . $emailLabel . '</span></label>
		<input id="email" name="email" type="email"'.$emailReg.'" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" placeholder="' . $emailLabel .'" tabindex="2"' . ($req ? ' required ':'') . '>'
);

$arg = array(
	'fields' => apply_filters('comment_form_default_fields', $fields),
                
    'must_log_in' => sprintf( __( 'You must be <a class="button" href="%s">logged in</a> to post a comment.'), wp_login_url( apply_filters( 'the_permalink', get_permalink() ) ) ),
	
	'logged_in_as' => '<ul class="button-group">
		<li><a class="button small" href="' . admin_url( "profile.php" ) . '"><i class="icon-user"></i> ' . $user_identity . '</a></li>
		<li><a class="button small alert" href="' . wp_logout_url( apply_filters( "the_permalink", get_permalink( ) ) ) . '"><i class="icon-ban-circle"></i> Log Out</a></li>
	</ul>',
	
	'comment_notes_before' => '',
	
	'comment_notes_after' => '',
    
    'id_form' => 'commentform',
    
    'id_submit' => 'submit',

    'title_reply' => '',
        
    'cancel_reply_link' => __( '&times;' ),
    
    'label_submit' => __( 'Submit Review' ),
);

if ( get_option('woocommerce_enable_review_rating') == 'yes' ) {
	$arg['comment_field'] = '<label for="rating">' . __( 'Rating', 'woocommerce' ) .'</label><select name="rating" id="rating">
		<option value="">'.__( 'Rate&hellip;', 'woocommerce' ).'</option>
		<option value="5">'.__( 'Perfect', 'woocommerce' ).'</option>
		<option value="4">'.__( 'Good', 'woocommerce' ).'</option>
		<option value="3">'.__( 'Average', 'woocommerce' ).'</option>
		<option value="2">'.__( 'Not that bad', 'woocommerce' ).'</option>
		<option value="1">'.__( 'Very Poor', 'woocommerce' ).'</option>
	</select>';
}

$arg['comment_field'] .= '<label for="comment"><i class="icon-comment-alt"></i><span class=""> ' . __( 'Review' ) . '</span></label>
<textarea id="comment" name="comment" cols="120" rows="9" placeholder="' . __( 'Your Review (required)' ) .'" tabindex="4" required></textarea>' . $woocommerce->nonce_field('comment_rating', true, false);
if (comments_open()) {
	comment_form( apply_filters( 'woocommerce_product_review_comment_form_args', $arg ) );
}
// Comments
$options = get_option('fin_theme_options');
$comments_warning = $options['comments_warning'];
if(post_password_required() && $comments_warning) { ?>
  <div class="alert-box secondary">
    <button type="button" class="close">&times;</button>
    <p><?php _e('This area is password protected. Enter the password to view comments.', 'fin'); ?></p>
	</div>
<?php } ?>