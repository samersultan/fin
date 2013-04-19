<?php
/**
 * Single product short description
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $post;

$excerpt = $post->post_excerpt;
$content = get_the_content();

if ( !$excerpt && !$content ) return; ?>
<section itemprop="description" class="product-description">
	<?php if($excerpt != '') { 
		echo apply_filters( 'woocommerce_short_description', $excerpt );
	}else {
		echo apply_filters( 'woocommerce_short_description', $content);
	} ?>
</section>