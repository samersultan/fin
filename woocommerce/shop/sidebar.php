<?php
/**
 * Sidebar
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$sidebars = array('Default');
if(is_front_page()){
	array_unshift($sidebars,'Home','Shop');
}elseif(is_single()){
	array_unshift($sidebars,'Product', 'Shop');
}elseif(is_category()){
	array_unshift($sidebars,'Shop','Category');
}elseif(is_tax()){
	array_unshift($sidebars,'Shop','Tag', 'Category');
}elseif(is_archive()){
	array_unshift($sidebars,'Shop','Archive','Category');
}elseif(is_search()){
	array_unshift($sidebars,'Shop','Search');
} ?>
<aside id="sidebar-<?php echo $sidebars[0]; ?>" class="sidebar" role="complementary">
	<?php // add sorting widgets to shop and category pages
	if(is_search() || is_category() || is_archive() || is_tax()) { ?>
		<section id="product_sorting" class="widget">
		<?php /**
		 * woocommerce_before_shop_loop hook
		 *
		 * @hooked woocommerce_result_count - 20
		 * @hooked woocommerce_catalog_ordering - 30
		 */
		do_action( 'woocommerce_before_shop_loop' ); ?>
		</section>
	<?php }	?>
	<?php //go through the $sidebars array until one is present
	foreach ($sidebars as $sidebar) {
		if(dynamic_sidebar($sidebar)) {
			break;
		}
	} ?>
</aside>