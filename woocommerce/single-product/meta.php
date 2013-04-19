<?php
/**
 * Single Product Meta
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $post, $product; ?>

<div class="product-meta">
	<?php // SKU
	if($product->is_type(array('simple','variable')) && get_option('woocommerce_enable_sku') == 'yes' && $product->get_sku()) { ?>
		<div class="meta_sku">
			<span itemprop="productID" class="sku_wrapper"><?php _e( 'SKU:', 'woocommerce' ); ?> <span class="sku"><?php echo $product->get_sku(); ?></span></span>
		</div>
	<?php }
	// Rating
	if(get_option('woocommerce_enable_review_rating') != 'no') {
		echo fin_get_average_rating($post);
	}
	// Categories
	$categories =  get_the_terms( $post->ID, 'product_cat' );
	if($categories && ! is_wp_error( $categories )) { ?>
		<ul class="meta_categories button-group">
			<?php foreach ($categories as $category) {
				echo '<li><a href="' . get_term_link($category->slug, 'product_cat') . '" rel="category tag" class="meta_category tiny secondary button"><i class="icon-folder-close"></i> ' . $category->name . '</a></li>';
			} ?>
		</ul>
	<?php }
	// Tags
	$tags = get_the_terms( $post->ID, 'product_tag' );
	if($tags && ! is_wp_error( $tags )){ ?>
		<ul class="meta_tags button-group">
			<?php foreach ($tags as $tag) {
				echo '<li><a href="' . get_term_link($tag->slug, 'product_tag') . '" rel="tag" class="meta_tag button tiny secondary button"><i class="icon-tags"></i> ' . $tag->name . '</a></li>';
			} ?>
		</ul>
	<?php }
	// Edit
	edit_post_link('<i class="icon-pencil"></i> edit');
	// Hook
	do_action( 'woocommerce_product_meta_end' ); ?>
</div>
