<?php
/**
 * Single Product title
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

?>
<h3 itemprop="name" class="product_title entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>