<?php 
// Remove woocommerce wrappers. We have them built into the theme files instead
remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper',10);
remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);

// remove checkout page coupon hook so we can move it lower on the page
remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10 );
// add_action( 'woocommerce_review_order_after_cart_contents', 'woocommerce_checkout_coupon_form', 10 );

// Register additional
$sidebars = array('Product', 'Shop', 'Cart', 'Checkout');
foreach ($sidebars as $sidebar) {
	register_sidebar(array('name'=> $sidebar,
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget' => '</section>',
		'before_title' => '<h4>',
		'after_title' => '</h4>'
	));
}

/**
 * Default Comment Structure
 *
 */
class Fin_Walker_Review extends Walker_Comment {
	function start_lvl(&$output, $depth = 0, $args = array()) {
		$GLOBALS['comment_depth'] = $depth + 1; ?>
		<ol <?php comment_class(); ?>>
	<?php }
	
	function end_lvl(&$output, $depth = 0, $args = array()) {
		$GLOBALS['comment_depth'] = $depth + 1;
		echo '</ol>';
	}
	
	function start_el(&$output, $comment, $depth, $args, $id = 0) {
		$depth++;
		$GLOBALS['comment_depth'] = $depth;
		$GLOBALS['comment'] = $comment;
		
		if(!empty($args['callback'])) {
			call_user_func($args['callback'], $comment, $args, $depth);
			return;
		}
		
		extract($args, EXTR_SKIP);
		$rating = esc_attr( get_comment_meta( $GLOBALS['comment']->comment_ID, 'rating', true ) );
		?>
		
		<li id="<?php echo 'comment-' . get_comment_ID(); ?>" <?php comment_class(); ?> itemprop="reviews" itemscope itemtype="http://schema.org/Review">
			<?php woocommerce_get_template_part( 'single-product/review', 'single-product' );
		 //</li> added below
	}
		
	function end_el(&$output, $comment, $depth = 0, $args = array()) {
		if (!empty($args['end-callback'])) {
			call_user_func($args['end-callback'], $comment, $args, $depth);
			return;
		}
		echo "</li>\n";
	}
}

/**
 * Description Tab
 *
 * Cleaned up to only show when both a long and short description are present
 * 
 */
function fin_description_tab($tabs) {
	global $post;
	
	$excerpt = $post->post_excerpt;
	$content = $post->post_content;
	if(!$excerpt || !$content) {
		unset($tabs['description']);
	}
	return $tabs;
}
add_action( 'woocommerce_product_tabs', 'fin_description_tab');

/**
 * Reviews Tab
 *
 * Cleaned up to only show when there are reviews
 *
 */
function fin_reviews_tab($tabs) {
	global $post;
	
	// Remove 'reviews' tab if there are no reviews
	$comment_count = get_comment_count($post->ID);
	if ( $comment_count['approved'] <= 0 ){
		unset($tabs['reviews']);
	}
	return $tabs;
}
function fin_reviews_tab_content() {
	woocommerce_get_template_part( 'single-product-reviews', 'tab' );
}
add_action( 'woocommerce_product_tabs', 'fin_reviews_tab');

/**
 * Create Review Form Tab
 *
 */
function fin_review_form_tab($tabs) {
	if(comments_open()) {
		// Adds the new tab
		$tabs['review_form'] = array(
			'title' 	=> __( 'Leave a Review', 'woocommerce' ),
			'priority' 	=> 40,
			'callback' 	=> 'fin_review_form_tab_content'
		);
	}
	return $tabs;
}
function fin_review_form_tab_content() {
	woocommerce_get_template_part( 'single-product/review-form', 'tab' );
}
add_action( 'woocommerce_product_tabs', 'fin_review_form_tab');

/**
 * Move related products to a tab
 *
 */
function fin_related_tab($tabs) {
	// check to see if there are related products before adding tab
	global $product;
	$related = $product->get_related();
	
	if(sizeof($related) > 0) {
		// Adds the new tab
		$tabs['related_products'] = array(
			'title' 	=> __( 'Related Products', 'woocommerce' ),
			'priority' 	=> 50,
			'callback' 	=> 'fin_related_tab_content'
		);
	}
 
	return $tabs;
}
function fin_related_tab_content() {
	woocommerce_related_products(4);
}
add_action( 'woocommerce_product_tabs', 'fin_related_tab');

/**
 * Get Woocommerce Average Rating
 *
 */
function fin_get_average_rating($post) {
	$output = '';
	if ( get_option('woocommerce_enable_review_rating') == 'yes' ) {
		global $wpdb;
		
		$count = $wpdb->get_var( $wpdb->prepare("
			SELECT COUNT(meta_value) FROM $wpdb->commentmeta
			LEFT JOIN $wpdb->comments ON $wpdb->commentmeta.comment_id = $wpdb->comments.comment_ID
			WHERE meta_key = 'rating'
			AND comment_post_ID = %d
			AND comment_approved = '1'
			AND meta_value > 0
		", $post->ID ) );
	
		$rating = $wpdb->get_var( $wpdb->prepare("
			SELECT SUM(meta_value) FROM $wpdb->commentmeta
			LEFT JOIN $wpdb->comments ON $wpdb->commentmeta.comment_id = $wpdb->comments.comment_ID
			WHERE meta_key = 'rating'
			AND comment_post_ID = %d
			AND comment_approved = '1'
		", $post->ID ) );
	
		if ( $count > 0 ) {
			$average = number_format($rating / $count, 2);
			$average = round($average*2) / 2;
			$rating = $average * 10;
			
			$output = '<div class="star-rating">';
			$output .= '<i class="rating-' . $rating . '"></i>';
			$output .= '</div>';
		}
	}
	return $output;
}

/**
 * Get WooCommerce Buy Button
 *
 */
function fin_add_to_cart($product) {
	// Add to Cart
	if(!$product->is_in_stock()) { ?>
		<a href="<?php echo apply_filters( 'out_of_stock_add_to_cart_url', get_permalink( $product->id ) ); ?>" class="button"><?php echo apply_filters( 'out_of_stock_add_to_cart_text', __( 'Read More', 'woocommerce' ) ); ?></a>
	<?php }else {
		if(is_singular( 'product' )) {
			do_action( 'woocommerce_' . $product->product_type . '_add_to_cart'  );
		}else {
			$link = array(
				'url'   => '',
				'label' => '',
				'class' => ''
			);
	
			$handler = apply_filters( 'woocommerce_add_to_cart_handler', $product->product_type, $product );
	
			switch ( $handler ) {
				case "variable" :
					$link['url'] 	= apply_filters( 'variable_add_to_cart_url', get_permalink( $product->id ) );
					$link['label'] 	= apply_filters( 'variable_add_to_cart_text', __( 'Select options', 'woocommerce' ) );
				break;
				case "grouped" :
					$link['url'] 	= apply_filters( 'grouped_add_to_cart_url', get_permalink( $product->id ) );
					$link['label'] 	= apply_filters( 'grouped_add_to_cart_text', __( 'View options', 'woocommerce' ) );
				break;
				case "external" :
					$link['url'] 	= apply_filters( 'external_add_to_cart_url', get_permalink( $product->id ) );
					$link['label'] 	= apply_filters( 'external_add_to_cart_text', __( 'Read More', 'woocommerce' ) );
				break;
				default :
					if ( $product->is_purchasable() ) {
						$link['url'] 	= apply_filters( 'add_to_cart_url', esc_url( $product->add_to_cart_url() ) );
						$link['label'] 	= apply_filters( 'add_to_cart_text', __( 'Add to cart', 'woocommerce' ) );
						$link['class']  = apply_filters( 'add_to_cart_class', 'add_to_cart_button' );
					} else {
						$link['url'] 	= apply_filters( 'not_purchasable_url', get_permalink( $product->id ) );
						$link['label'] 	= apply_filters( 'not_purchasable_text', __( 'Read More', 'woocommerce' ) );
					}
				break;
			}
	
			echo apply_filters( 'woocommerce_loop_add_to_cart_link', sprintf('<a href="%s" rel="nofollow" data-product_id="%s" data-product_sku="%s" class="%s button product_type_%s">%s</a>', esc_url( $link['url'] ), esc_attr( $product->id ), esc_attr( $product->get_sku() ), esc_attr( $link['class'] ), esc_attr( $product->product_type ), esc_html( $link['label'] ) ), $product, $link );
		}
	}
}