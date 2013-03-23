<?php 
/**
 * [rotator] shortcode
 *
 * Output posts from the base_rotator custom post type
 * Use location="" attribute to pull in posts from a specific location
 * from the fin_rotator_location taxonomy
 *
 * Example:
 * [rotator location="home"]
 */
function shortcode_rotator($atts) {
  extract(shortcode_atts(array(
    'location' => ''
  ), $atts));
  global $rotator_loc;
  $rotator_loc = $location;
  ob_start();
  get_template_part('templates/rotator', $location);
  $rotator = ob_get_contents();
  ob_end_clean();
  return $rotator;
}
add_shortcode('rotator', 'shortcode_rotator');

/**
 * [gallery] shortcode
 *
 * Remove the standard gallery and enhance it.
 *
 * Example:
 * [gallery]
 */
// Remove built in shortcode
remove_shortcode('gallery', 'gallery_shortcode');

// Replace with custom shortcode
function shortcode_gallery($attr) {
	$post = get_post();
	
	static $instance = 0;
	$instance++;

	if (!empty($attr['ids'])) {
		if (empty($attr['orderby'])) {
			$attr['orderby'] = 'post__in';
		}
		$attr['include'] = $attr['ids'];
	}

	$output = apply_filters('post_gallery', '', $attr);

	if ($output != '') {
		return $output;
	}

	if (isset($attr['orderby'])) {
		$attr['orderby'] = sanitize_sql_orderby($attr['orderby']);
		if (!$attr['orderby']) {
			unset($attr['orderby']);
		}
	}

	extract(shortcode_atts(array(
		'order'      => 'ASC',
		'orderby'    => 'menu_order ID',
		'id'         => $post->ID,
		'itemtag'    => '',
		'icontag'    => '',
		'captiontag' => '',
		'columns'    => 4,
		'size'       => 'thumbnail',
		'include'    => '',
		'exclude'    => ''
	), $attr));

	$id = intval($id);

	if ($order === 'RAND') {
		$orderby = 'none';
	}

	if (!empty($include)) {
		$_attachments = get_posts(array('include' => $include, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby));

		$attachments = array();
		foreach ($_attachments as $key => $val) {
			$attachments[$val->ID] = $_attachments[$key];
		}
	} elseif (!empty($exclude)) {
		$attachments = get_children(array('post_parent' => $id, 'exclude' => $exclude, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby));
	} else {
		$attachments = get_children(array('post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby));
	}

	if (empty($attachments)) {
		return '';
	}

	if (is_feed()) {
		$output = "\n";
		foreach ($attachments as $att_id => $attachment) {
			$output .= wp_get_attachment_link($att_id, $size, true) . "\n";
		}
		return $output;
	}
	
	if($columns > 4) {
		$grid='small-block-grid-4 large-block-grid-' . $columns;
	}else {
		$grid='small-block-grid-' . $columns;
	}
	
	$output = '<ul class="clearing-thumbs ' . $grid . '" data-clearing>';

	foreach ($attachments as $id => $attachment) {
		$attachmentURL = 
		$imageURL = wp_get_attachment_url($id);
		$thumb = wp_get_attachment_image_src($id, $size);
		$thumbURL = $thumb[0];
		if (trim($attachment->post_excerpt)) {
			$caption = ' data-caption="' . wptexturize($attachment->post_excerpt) . '"';
		}else {
			$caption = '';
		}
		$output .= '<li><a class="th" href="' . $imageURL . '"><img src="' . $thumbURL . '"' . $caption . '></a></li>';
	}

	$output .= '</ul>';
	return $output;
}
add_shortcode('gallery', 'shortcode_gallery');

/**
 * [email] shortcode
 * 
 * Encodes and creates an email link
 *
 * Examples:
 * [email]you@url.com[/email]
 *
 * [email address="you@url.com"]Your Name[/email]
 *
 * [email address="you@url.com" label="Your Name"]
 */
function shortcode_email( $atts, $content = null ) {
	extract( shortcode_atts( array(
	'address' => '',
	'label' => '', 
	), $atts ) );
	
	if($address == '') {
		$address = do_shortcode($content);
	}elseif($content != '') {
		$label = do_shortcode($content);
	}
	if($label == '') {
		$label = antispambot($address);
	}
	 
	return '<a href="mailto:' . antispambot($address) . '">' . $label . '</a>';
}
add_shortcode('email', 'shortcode_email');

/**
 * [tel] shortcode
 * 
 * Encodes and creates an telephone link
 *
 * Examples:
 * [tel]555-555-1234[/tel]
 *
 * [email number="555-555-1234"]Call Me[/email]
 *
 * [email number="555-555-1234" label="Call Me"]
 */
function shortcode_tel( $atts, $content = null ) {
	extract( shortcode_atts( array(
	'number' => '',
	'label' => '', 
	), $atts ) );
	
	if($number == '') {
		$number = do_shortcode($content);
	}elseif($content != '') {
		$label = do_shortcode($content);
	}
	if($label == '') {
		$label = antispambot($number);
	}
	$number = preg_replace('/[^0-9]/', '', $number); //strip out all non-numeric characters
	if(substr($number, 0, 1) == 1) { // remove leading 1 if it's there
		$number = substr($number, 1);
	}
	return '<a href="tel:+1' . antispambot($number) . '">' . $label . '</a>';
}
add_shortcode('tel', 'shortcode_tel');

/**
 * [child-pages] [sibling-pages] [list-pages] shortcodes
 *
 * Creates a list of the appropriate pages
 *
 * Example:
 * [child-pages]
 * will output:
 * <ul>
 *   <li><a href="/child1">Child Page #1</a></li>
 *   <li><a href="/child2">Child Page #2</a></li>
 * </ul>
 */
function shortcode_list_pages( $atts, $content, $tag ) {
 	global $post;
 	
 	// Child Pages
 	$child_of = 0;
 	if ( $tag == 'child-pages' )
 		$child_of = $post->ID;
 	if ( $tag == 'sibling-pages' )
 		$child_of = $post->post_parent;
 	
 	// Set defaults
 	$defaults = array(
 		'class'       => $tag,
 		'depth'       => 0,
 		'show_date'   => '',
 		'date_format' => get_option( 'date_format' ),
 		'exclude'     => '',
 		'include'     => '',
 		'child_of'    => $child_of,
 		'title_li'    => '',
 		'authors'     => '',
 		'sort_column' => 'menu_order, post_title',
 		'sort_order'  => '',
 		'link_before' => '',
 		'link_after'  => '',
 		'exclude_tree'=> '',
 		'meta_key'    => '',
 		'meta_value'  => '',
 		'offset'      => '',
 		'exclude_current_page' => 0
 	);
 	
 	// Merge user provided atts with defaults
 	$atts = shortcode_atts( $defaults, $atts );
 	
 	// Set necessary params
 	$atts['echo'] = 0;
 	if ( $atts['exclude_current_page'] && absint( $post->ID ) ) {
 		if ( !empty( $atts['exclude'] ) )
 			$atts['exclude'] .= ',';
 		$atts['exclude'] .= $post->ID;
 	}
 	
 	$atts = apply_filters( 'shortcode_list_pages_attributes', $atts, $content, $tag );
 	
 	// Create output
 	$out = wp_list_pages( $atts );
 	if ( !empty( $out ) )
		$out = '<ul class="' . $atts['class'] . '">' . $out . '</ul>';
 	
	return apply_filters( 'shortcode_list_pages', $out, $atts, $content, $tag );
 	
}
add_shortcode( 'child-pages', 'shortcode_list_pages' );
add_shortcode( 'sibling-pages', 'shortcode_list_pages' );
add_shortcode( 'list-pages', 'shortcode_list_pages' );
 
 /**
  * [row] shortcode
  *
  * Creates a row 
  *
  * Example:
  * [row][/row]
  */
  
function shortcode_row( $atts, $content = null ) {
	return '<div class="row">' . do_shortcode($content) . '</div>';
}
  
 add_shortcode( 'row', 'shortcode_row' );
  
 /**
  * [column] shortcode
  *
  * Creates a column
  *
  * Example:
  * [column span="(small-1, small-12 large-4... one-third, three-fourths...)" offset="(one, two, three...)" centered="(true, false)"][/column]
  */
function shortcode_column( $atts, $content = null ) {
	extract( shortcode_atts( array(
		'centered' => '',
		'span' => '',
		'offset' => ''
		), $atts ) );
		
	if($centered != '') {
		$centered = ' ' . $centered;
	}
	
	if($span != '') {
		$span = ' ' . $span;
	}
	
	if($offset != '') {
		$offset = ' offset-by-' . $offset;
	}

	return '<div class="' . esc_attr($span) . esc_attr($offset) . esc_attr($centered) . '">' . do_shortcode($content) . '</div>';
}
add_shortcode( 'column', 'shortcode_column' );
 
 /**
  * [button] shortcode
  *
  * Creates a button
  *
  * Examples:
  * [button type="(radius round)" size="(mini small large)" type="(alert, success, secondary, disabled)" url="http://#"]This is a button[/button]
  * or
  * [button text="This is a button." url="http://#"]
  */
function shortcode_button( $atts, $content = null ) {
	extract( shortcode_atts( array(
		'type' => 'radius', /* radius, round */
		'size' => 'medium', /* small, medium, large */
		'type' => 'secondary', /* alert, success, secondary, disabled */
		'url'  => '',
		'text' => '', 
		), $atts ) );
	
	if($text == ''){
		$text = do_shortcode($content);
	}
	
	$output = '<a href="' . $url . '" class="button '. $type . ' ' . $size;
	$output .= '">';
	$output .= $text;
	$output .= '</a>';
	
	return $output;
}
add_shortcode('button', 'shortcode_button'); 
  
 /**
  * [alert] shortcode
  *
  * Creates an alert
  *
  * Examples:
  * [alert type="(alert, success, secondary)" close="(true false)"]This is an alert[/alert]
  * or
  * [alert text="This is an alert."]
  */
function shortcode_alert( $atts, $content = null ) {
	extract( shortcode_atts( array(
	'type' => '  ', /* alert, success, secondary */
	'close' => 'true', /* display close link */
	'text' => '', 
	), $atts ) );
	
	if($text == '') {
		$text = do_shortcode($content);
	}
	
	$output = '<div data-alert class="alert-box '. $type . '">';
	$output .= $text;
	if($close == 'true') {
		$output .= '<a href="#" class="close">&times;</a>';
	}
	$output .= '</div>';
	
	return $output;
}
add_shortcode('alert', 'shortcode_alert');
  
 /**
  * [panel] shortcode
  *
  * Creates a panel
  *
  * Example:
  * [panel]This is panel[/panel]
  * or
  * [panel text="This is a panel."]
  */
function shortcode_panel( $atts, $content = null ) {
	extract( shortcode_atts( array(
	'type' => '  ', /* callout */
	'close' => 'false', /* display close link */
	'text' => '', 
	), $atts ) );
	
	if($text == ''){
		$text = do_shortcode($content);
	}
	
	$output = '<div class="panel">';
	$output .= $text;
	$output .= '</div>';
	
	return $output;
}
add_shortcode('panel', 'shortcode_panel');
 
/**
 * [modal] shortcode
 *
 * Creates a modal that is automatically launched (default) or launched by a button
 *
 * Examples:
 * [modal]This is the text that will automatically pop-up when the page loads[/modal]
 *
 * [modal button="launch modal"]This text will load when the user presses the button created![/modal]
 **/
class fin_modal {
	protected static $modal;
	
	public static function shortcode_callback( $atts, $content = null) {
		extract( shortcode_atts( array(
		'text' => '',
		'button' => '',
		'size' => '', 
		), $atts ) );
		
		// get unique modal number 
		$modalNum = substr(uniqid(), -4); //last 4 digits of uniqid will suffice
		if($text == '') {
			$text = do_shortcode($content);
		}
		
		self::$modal .= '<div id="modal-' . $modalNum . '" class="reveal-modal' . $size . '" role="dialog">';
		self::$modal .= $text . '<a href="#" type="button" class="close-reveal-modal">&times;</a>';
		self::$modal .= '</div>';
		
		if($button != '') {
			$button = '<a href="#" data-reveal-id="modal-' . $modalNum . '" role="button" class="button">' . $button . '</a>';
		}else {
			self::$modal .= '<script type="text/javascript">
		        jQuery("#modal-' . $modalNum . '").foundation("reveal","open");
			</script>';
		}
		
		add_action('wp_footer', array( __CLASS__, 'footer' ), 300);
		
		return $button;
	}
	public static function footer() {
		echo self::$modal;
	}
}
add_shortcode('modal', array('fin_modal', 'shortcode_callback'));