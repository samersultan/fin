<?php
/**
 * Shipping Calculator
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $woocommerce;

if ( get_option('woocommerce_enable_shipping_calc')=='no' || ! $woocommerce->cart->needs_shipping() ) return;
?>

<?php do_action( 'woocommerce_before_shipping_calculator' ); ?>

<form class="shipping_calculator" action="<?php echo esc_url( $woocommerce->cart->get_cart_url() ); ?>" method="post">
	<h2><a href="#" class="shipping-calculator-button"><?php _e( 'Calculate Shipping', 'woocommerce' ); ?> <span>&darr;</span></a></h2>
	<section class="shipping-calculator-form">
		<select name="calc_shipping_country" id="calc_shipping_country" class="country_to_state" rel="calc_shipping_state">
			<option value=""><?php _e( 'Select a country&hellip;', 'woocommerce' ); ?></option>
			<?php
				foreach( $woocommerce->countries->get_allowed_countries() as $key => $value )
					echo '<option value="' . $key . '"' . selected( $woocommerce->customer->get_shipping_country(), esc_attr( $key ), false ) . '>' . esc_html( $value ) . '</option>';
			?>
		</select>
		<?php
			$current_cc = $woocommerce->customer->get_shipping_country();
			$current_r = $woocommerce->customer->get_shipping_state();

			$states = $woocommerce->countries->get_states( $current_cc );

			if ( is_array( $states ) && empty( $states ) ) {

				// Hidden
				?>
				<input type="hidden" name="calc_shipping_state" id="calc_shipping_state" placeholder="<?php _e( 'State / county', 'woocommerce' ); ?>" />
				<?php

			} elseif ( is_array( $states ) ) {

				// Dropdown
				?>
				<span>
					<select name="calc_shipping_state" id="calc_shipping_state" placeholder="<?php _e( 'State / county', 'woocommerce' ); ?>"><option value=""><?php _e( 'Select a state&hellip;', 'woocommerce' ); ?></option><?php
						foreach ( $states as $ckey => $cvalue )
							echo '<option value="' . esc_attr( $ckey ) . '" '.selected( $current_r, $ckey, false ) .'>' . __( esc_html( $cvalue ), 'woocommerce' ) .'</option>';
					?></select>
				</span>
				<?php

			} else {

				// Input
				?>
				<input type="text" class="input-text" value="<?php echo esc_attr( $current_r ); ?>" placeholder="<?php _e( 'State / county', 'woocommerce' ); ?>" name="calc_shipping_state" id="calc_shipping_state" />
				<?php

			}
		?>
			<input type="text" class="input-text" value="<?php echo esc_attr( $woocommerce->customer->get_shipping_postcode() ); ?>" placeholder="<?php _e( 'Postcode / Zip', 'woocommerce' ); ?>" title="<?php _e( 'Postcode', 'woocommerce' ); ?>" name="calc_shipping_postcode" id="calc_shipping_postcode" />
			<button type="submit" name="calc_shipping" value="1" class="button"><?php _e( 'Update Totals', 'woocommerce' ); ?></button>
		<?php $woocommerce->nonce_field('cart') ?>
	</section>
</form>

<?php do_action( 'woocommerce_after_shipping_calculator' ); ?>
