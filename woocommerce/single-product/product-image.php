<?php
/**
 * Single Product Image
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $post, $product, $woocommerce;

$attachment_ids = $product->get_gallery_attachment_ids();
if($attachment_ids || has_post_thumbnail($post->ID)) { ?>
	<div class="images product-gallery">
		<?php if(has_post_thumbnail($post->ID)) { ?>
			<a class="woocommerce-main-image zoom" itemprop="image" href="<?php echo wp_get_attachment_url( get_post_thumbnail_id() ); ?>" title="<?php echo get_the_title( get_post_thumbnail_id() ); ?>"><?php echo get_the_post_thumbnail( $post->ID, apply_filters( 'single_product_large_thumbnail_size', 'shop_single' ) ) ?></a>
		<?php }
		do_action('woocommerce_product_thumbnails'); ?>
	</div>
<?php } ?>