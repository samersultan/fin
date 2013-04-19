<?php
/**
 * Checkout coupon form
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $woocommerce;

if ( ! $woocommerce->cart->coupons_enabled() ) {
	return;
} ?>

<form class="checkout_coupon row collapse input-group" method="post" style="display: block !important;">
	<input name="coupon_code" class="input-text" id="coupon_code" value="" type="text" placeholder="Coupon Code">
	<input type="submit" class="button postfix" name="apply_coupon" value="<?php _e( 'Apply', 'woocommerce' ); ?>">
</form>