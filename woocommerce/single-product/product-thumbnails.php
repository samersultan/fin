<?php
/**
 * Single Product Thumbnails
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $post, $product, $woocommerce;

$attachment_ids = $product->get_gallery_attachment_ids();

if($attachment_ids) { ?>
	<ul class="clearing-thumbs" data-clearing>
		<?php // get images from product-gallery
		// save id to array to compare against product variations
		$galleryThumbs = array();
		foreach ($attachment_ids as $id) {
			$attachment_url = wp_get_attachment_url( $id );
			if(!$attachment_url) {
				continue;
			}else { 
				array_push($galleryThumbs, $id); ?>
				<li><a href="<?php echo esc_attr( $attachment_url ); ?>"><?php echo wp_get_attachment_image( $id, apply_filters( 'single_product_small_thumbnail_size', 'shop_thumbnail')); ?></a></li>
			<?php }
		} 
		// Get featured image and add to list as hidden
		if(has_post_thumbnail($post->ID)) { ?>
			<li class="hide"><a href="<?php echo wp_get_attachment_url( get_post_thumbnail_id() ); ?>"><?php echo get_the_post_thumbnail( $post->ID, apply_filters( 'single_product_large_thumbnail_size', 'shop_thumbnail' ) ) ?></a></li>
		<?php }
		// Get product variation thumbnails but don't display duplicates. Set class to hide
		if ( $product->is_type( 'variation' )) {
			foreach ($available_variations as $variation) {
				if($variation['image_link'] != '') {
					$imageID = get_post_thumbnail_id( $variation['variation_id'] );
					if(!in_array($imageID, $galleryThumbs)) { ?>
						<li class="hide"><a rel="lightbox" href="<?php echo $variation['image_link']; ?>"><img src="<?php echo esc_attr($variation['image_src']); ?>" alt="<?php echo $variation['image_title']; ?>"></a></li>
					<?php }
				}
			}
		} ?>
	</ul>
<?php } ?>