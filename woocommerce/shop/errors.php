<?php
/**
 * Show error messages
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! $errors ) return;
?>
<div class="alert-box alert" data-alert>
	<ul class="errors">
		<?php foreach ( $errors as $error ) : ?>
			<li><?php echo wp_kses_post( $error ); ?></li>
		<?php endforeach; ?>
	</ul>
	<a href="#" class="close">&times;</a>
</div>