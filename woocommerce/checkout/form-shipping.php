<?php
/**
 * Checkout shipping information form
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $woocommerce;
?>

<?php if ( ( $woocommerce->cart->needs_shipping() || get_option('woocommerce_require_shipping_address') == 'yes' ) && ! $woocommerce->cart->ship_to_billing_address_only() ) : ?>

	<?php
		if ( empty( $_POST ) ) {

			$shiptobilling = (get_option('woocommerce_ship_to_same_address')=='yes') ? 1 : 0;
			$shiptobilling = apply_filters('woocommerce_shiptobilling_default', $shiptobilling);

		}else {

			$shiptobilling = $checkout->get_value('shiptobilling');

		}
	?>
	<h3><?php _e( 'Shipping Address', 'woocommerce' ); ?></h3>

	<div class="shipping_address">

		<?php do_action('woocommerce_before_checkout_shipping_form', $checkout); ?>

		<?php foreach ($checkout->checkout_fields['shipping'] as $key => $field) : ?>

			<?php woocommerce_form_field( $key, $field, $checkout->get_value( $key ) ); ?>

		<?php endforeach; ?>

		<?php do_action('woocommerce_after_checkout_shipping_form', $checkout); ?>

	</div>

<?php endif; ?>

<?php do_action('woocommerce_before_order_notes', $checkout); ?>

<?php if (get_option('woocommerce_enable_order_comments')!='no') : ?>

	<?php if ($woocommerce->cart->ship_to_billing_address_only()) : ?>

		<h3><?php _e( 'Additional Information', 'woocommerce' ); ?></h3>

	<?php endif; ?>	
	<div class="row">
		<p class="small-8 columns"><?php _e( 'Ship to billing address?', 'woocommerce' ); ?></p>
		<div class="switch small round small-4">
		  <input id="shiptobilling-checkbox" name="shiptobilling" type="radio" onclick="$('.shipping_address').slideDown();">
		  <label for="shiptobilling-checkbox"> No</label>
		  <input id="shiptobilling-checkbox-on" name="shiptobilling" type="radio" <?php checked($shiptobilling, 1); ?> onclick="$('.shipping_address').slideUp();">
		  <label for="shiptobilling-checkbox-on">Yes </label>
		  <span></span>
		</div>
	</div>
	<?php foreach ($checkout->checkout_fields['order'] as $key => $field) : ?>

		<?php woocommerce_form_field( $key, $field, $checkout->get_value( $key ) ); ?>

	<?php endforeach; ?>
	
<?php endif; ?>

<?php do_action('woocommerce_after_order_notes', $checkout); ?>