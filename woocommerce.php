<?php 
/**
 * WooCommerce Template Functions
 *
 * Functions used in the template files to output content - in most cases hooked in via the template actions. All functions are pluggable.
 *
 * @author 		WooThemes
 * @category 	Core
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */
 
if(!have_posts()) {
	if ( ! woocommerce_product_subcategories( array( 'before' => woocommerce_product_loop_start( false ), 'after' => woocommerce_product_loop_end( false ) ) ) ) {
		woocommerce_get_template( 'loop/no-products-found.php' );
	}
}elseif(is_singular( 'product')) {
	woocommerce_get_template_part( 'single-product' );
}else {
	woocommerce_get_template( 'archive-product.php' );
} ?>