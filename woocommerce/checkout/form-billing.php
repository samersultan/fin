<?php
/**
 * Checkout billing information form
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $woocommerce;
?>

<?php if ( $woocommerce->cart->ship_to_billing_address_only() && $woocommerce->cart->needs_shipping() ) : ?>

	<h3><?php _e( 'Billing &amp; Shipping', 'woocommerce' ); ?></h3>

<?php else : ?>

	<h3><?php _e( 'Billing Address', 'woocommerce' ); ?></h3>

<?php endif; ?>

<?php do_action('woocommerce_before_checkout_billing_form', $checkout ); ?>

<?php foreach ($checkout->checkout_fields['billing'] as $key => $field) : ?>

	<?php woocommerce_form_field( $key, $field, $checkout->get_value( $key ) ); ?>

<?php endforeach; ?>

<?php do_action('woocommerce_after_checkout_billing_form', $checkout ); ?>



<?php if ( ! is_user_logged_in() && $checkout->enable_signup ) : ?>

	<?php if ( $checkout->enable_guest_checkout ) : ?>
		<div class="row">
			<p class="small-8 columns"><?php _e( 'Create An Account?', 'woocommerce' ); ?></p>
			<div class="switch small round small-4 columns">
			  <input id="createaccount-off" name="createaccount" type="radio" <?php checked($checkout->get_value('createaccount'), false) ?> onclick="$('.create-account').slideUp();">
			  <label for="createaccount"> No</label>
			  <input id="createaccount-on" name="createaccount" type="radio" onclick="$('.create-account').slideDown();">
			  <label for="createaccount-on">Yes </label>
			  <span></span>
			</div>
		</div>

	<?php endif; ?>

	<?php do_action( 'woocommerce_before_checkout_registration_form', $checkout ); ?>

	<div class="create-account">

		<p><?php _e( 'Create an account by entering the information below. If you are a returning customer please login at the top of the page.', 'woocommerce' ); ?></p>

		<?php foreach ($checkout->checkout_fields['account'] as $key => $field) : ?>

			<?php woocommerce_form_field( $key, $field, $checkout->get_value( $key ) ); ?>

		<?php endforeach; ?>

	</div>

	<?php do_action( 'woocommerce_after_checkout_registration_form', $checkout ); ?>

<?php endif; ?>