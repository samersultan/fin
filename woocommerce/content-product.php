<?php
/**
 * The template for displaying product content within loops.
 *
 * Override this template by copying it to yourtheme/woocommerce/content-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $product; 
// Ensure visibility
if ( ! $product->is_visible()) {
	return;
} ?>

<?php do_action( 'woocommerce_before_shop_loop_item' ); ?>

<li <?php post_class(); ?>>
	<?php if(has_post_thumbnail($post->ID)) { ?>
		<figure class="entry-thumbnail">
			<a href="<?php the_permalink(); ?>">
				<?php echo get_the_post_thumbnail($post->ID, apply_filters( 'single_product_large_thumbnail_size', 'shop_catalog' )); ?>
			</a>
		</figure>
	<?php } ?>
	<header>
		<h5 class="product-title">
			<?php if($product->is_on_sale()) {
				echo apply_filters('woocommerce_sale_flash', '<span class="onsale">'.__( 'Sale!', 'fin' ).'</span>', $post, $product);
			} ?>
			<a href="<?php the_permalink(); ?>">
				<?php the_title();
				if($price_html = $product->get_price_html()) { ?>
					<small class="price"> - <?php echo $price_html; ?></small>
				<?php } ?>
			</a>
		</h5>
	</header>
	<footer>
		<?php // Rating
			echo fin_get_average_rating($post);
			do_action( 'woocommerce_after_shop_loop_item' ); ?>
	</footer>
</li>