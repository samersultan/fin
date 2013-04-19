<?php
/**
 * Review Comments Template
 *
 * Closing li is left out on purpose!
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $post, $comment;
$rating = esc_attr( get_comment_meta( $GLOBALS['comment']->comment_ID, 'rating', true ) );
?>
<?php // get avatar
$avatar = fin_get_avatar($comment, $size = '64');
if($avatar) { ?>
	<figure class="avatar">
		<a href="<?php echo htmlspecialchars(get_comment_link($comment->comment_ID)); ?>">
			<?php echo $avatar; ?>
		</a>
	</figure>
<?php } ?>
<header>
	<?php // Verified Commenter
	$verified = '';
	if ( get_option('woocommerce_review_rating_verification_label') == 'yes' ) {
		if ( woocommerce_customer_bought_product( $GLOBALS['comment']->comment_author_email, $GLOBALS['comment']->user_id, $post->ID ) ) {
			$verified = ' <em class="verified">(' . __( 'verified owner', 'woocommerce' ) . ')</em> ';
		}
	} ?>
	<cite class="fn" itemprop="author">by <?php echo get_comment_author_link() . $verified; ?></cite>
	<div class="star-rating" itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating">
		<i class="rating-<?php echo $rating * 10; ?>"></i>
	</div>
	<time datetime="<?php echo comment_date('c'); ?>" itemprop="datePublished"><a href="<?php echo htmlspecialchars(get_comment_link($comment->comment_ID)); ?>"><?php echo get_time_ago(get_comment_time('U')); ?></a></time>
</header>
<section itemprop="description">
	<?php if ($comment->comment_approved == '0') { ?>
		<div data-alert class="alert-box secondary">
			<a href="#" class="close">&times;</a>
			<i class="icon-magic"></i> <?php _e('Awaiting Moderation.', 'fin'); ?>
		</div>
	<?php }
	echo get_comment_text(); ?>
</section>
<footer>
	<?php edit_comment_link('<i class="icon-pencil"></i> ' . __('edit', 'fin'), '', ''); ?>
</footer>
