<?php
/**
 * Show messages
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! $messages ) return;
?>

<?php foreach ( $messages as $message ) : ?>
	<div data-alert class="alert-box secondary shop-message"><?php echo wp_kses_post( $message ); ?><a href="#" class="close">&times;</a></div>
<?php endforeach; ?>
