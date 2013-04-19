<?php
/**
 * Mini-cart
 *
 * Contains the markup for the mini-cart, used by the cart widget
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $woocommerce;

do_action( 'woocommerce_before_mini_cart' ); ?>
<?php if(sizeof($woocommerce->cart->get_cart() ) > 0 ) { ?>
	<ul class="unstyled mini_cart product_list_widget <?php echo $args['list_class']; ?>">
		<?php foreach ( $woocommerce->cart->get_cart() as $cart_item_key => $cart_item ) {
			$_product = $cart_item['data'];
			
			// Only display if allowed
			if ( ! apply_filters('woocommerce_widget_cart_item_visible', true, $cart_item, $cart_item_key ) || ! $_product->exists() || $cart_item['quantity'] == 0 ) {
				continue;
			}
			
			// Get price
			$product_price = get_option( 'woocommerce_tax_display_cart' ) == 'excl' ? $_product->get_price_excluding_tax() : $_product->get_price_including_tax();

			$product_price = apply_filters( 'woocommerce_cart_item_price_html', woocommerce_price( $product_price ), $cart_item, $cart_item_key ); ?>
			<li>
				<a href="<?php echo get_permalink( $cart_item['product_id'] ); ?>">
					<?php echo $_product->get_image($size='shop_thumbnail'); ?>
					<?php echo apply_filters('woocommerce_widget_cart_product_title', $_product->get_title(), $_product ); ?>
				</a>
				<span class="amount"><?php printf( '%s &times; %s', $cart_item['quantity'], $product_price ); ?></span>
			</li>
		<?php } ?>
	</ul>
	<h5 class="subtotal"><?php _e( 'Subtotal', 'woocommerce' ); ?>: <?php echo $woocommerce->cart->get_cart_subtotal(); ?></h5>
	<div class="button-group">
		<a href="<?php echo $woocommerce->cart->get_cart_url(); ?>" class="button tiny"><?php _e( 'View Cart', 'woocommerce' ); ?></a>
		<a href="<?php echo $woocommerce->cart->get_checkout_url(); ?>" class="button tiny checkout"><?php _e( 'Checkout', 'woocommerce' ); ?></a>
	</div>
<?php }else { ?>
	<p><?php _e( 'No products in the cart.', 'woocommerce' ); ?></p>
<?php }
do_action( 'woocommerce_after_mini_cart' ); ?>

