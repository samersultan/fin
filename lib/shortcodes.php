<?php 
/**
 * [code] and [pre] shortcodes
 * 
 * wraps content in <code></code> or <pre></pre> tags
 * also overrides all shortcodes contained inside content
 * 
 */
 
function shortcode_code( $atts, $content = null, $tag ) {
	$content = clean_pre($content);
	$content = str_replace('<', '<', $content);
	return '<' . $tag . '>' . $content . '</' . $tag . '>';
}
add_shortcode( 'code', 'shortcode_code' );
add_shortcode( 'pre', 'shortcode_code' ); 

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
 * [caption] shortcode
 *
 * Fixes the default wordpress caption output
 */
function shortcode_caption($output, $attr, $content) {
	if (is_feed()) {
    return $output;
  }
  $defaults = array(
		'id'      => '',
		'align'   => 'alignnone',
		'width'   => '',
		'caption' => ''
	);

	$attr = shortcode_atts($defaults, $attr);

	// If the width is less than 1 or there is no caption, return the content wrapped between the [caption] tags
	if ($attr['width'] < 1 || empty($attr['caption'])) {
		return $content;
	}

	// Set up the attributes for the caption <figure>
	$attributes  = (!empty($attr['id']) ? ' id="' . esc_attr($attr['id']) . '"' : '' );
	$attributes .= ' class="thumbnai ' . esc_attr($attr['align']) . '"';

	$output  = '<figure' . $attributes .'>';
	$output .= do_shortcode($content);
	$output .= '<figcaption class="caption">' . $attr['caption'] . '</figcaption>';
	$output .= '</figure>';

  return $output;
}
add_filter('img_caption_shortcode', 'shortcode_caption', 10, 3);

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
 * [tel] / [phone] shortcode
 * 
 * Encodes and creates an telephone link
 *
 * Examples:
 * [tel]555-555-1234[/tel]
 *
 * [tel number="555-555-1234"]Call Me[/email]
 *
 * [tel number="555-555-1234" label="Call Me"]
 */
function shortcode_tel( $atts, $content = null ) {
	extract( shortcode_atts( array(
	'number' => '',
	'label' => '',
	'button' => '',
	'style' => '',
	'text' => '',
	'SMS' => '',
	), $atts ) );
	
	// Set $number and $label based on available info
	if($number == '') {
		$number = $content;
	}elseif($content != '') {
		$label = $content;
	}elseif(is_string($button)) {
		$label = $button;
	}elseif($label == '') {
		$label = antispambot($number);
	}
	
	// Set $link
	$number = preg_replace('/[^0-9]/', '', $number); //strip out all non-numeric characters
	if(!substr($number, 0, 1) == 1) { // add leading 1 if it's not there
		$number = '+1' . $number;
	}
	// Text or Call?
	if($text == true || $SMS == true) {
		$link = 'sms:' . antispambot($number);
	}else {
		$link = 'tel:+1' . antispambot($number);
	}
	
	// Set $class
	if($button != '' || $style != '') {
		$class = 'class="button ' . $style;
		$class .= '" ';
	}else {
	 $class = '';
	}
	return '<a ' . $class . 'href="' . $link . '">' . $label . '</a>';
}
add_shortcode('tel', 'shortcode_tel');
add_shortcode('phone', 'shortcode_tel');

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
  * [column] or [span] shortcode
  *
  * Creates a column
  *
  * Example:
  * [column span="(small-1, small-12 large-4... one-third, three-fourths...)" offset="(one, two, three...)" centered="(true, false)"][/column]
  */
function shortcode_column( $atts, $content = null ) {
	extract( shortcode_atts( array(
		'span' => '',
		'centered' => '',
		'offset' => '',
		), $atts ) );
	
	// get span as [column span=""] or first variable	[column "small-4 large-8"]
	if($span != '') {
		$span = $span;
	}else {
		$span = $atts[0];
	}
	// if span is just an integer, add large-#
	if(is_integer($span)) {
		$span = 'large-' . $span;
	}
	
	
	if($centered != '') {
		$centered = ' ' . $centered;
	}
	
	if($offset != '') {
		$offset = ' offset-by-' . $offset;
	}

	return '<div class="column ' . esc_attr($span) . esc_attr($offset) . esc_attr($centered) . '">' . do_shortcode($content) . '</div>';
}
add_shortcode( 'column', 'shortcode_column' );
add_shortcode( 'span', 'shortcode_column' );

 /**
  * [button] shortcode
  *
  * Creates a button
  *
  * Examples:
  * [button style="(radius round)(mini small large)(alert, success, secondary, disabled)" url/link="http://#"]This is a button[/button]
  * or
  * [button text="This is a button." url="http://#"]
  */
function shortcode_button( $atts, $content = null ) {
	extract( shortcode_atts( array(
		'url'  => '',
		'link' => '',
		'style' => '', /* radius, round */
		'text' => '', 
		), $atts ) );
	
	if($text == ''){
		$text = do_shortcode($content);
	}
	// Allow user to use link="" or url=""
	if($link != '') {
			$url = $link;
	}elseif($url == '') {
		$url = $atts[0];
	}
	// Add http:// if user did not include it
	if(!strpos($url, 'http') === 0) { 
		$url = 'http://' . $url;
	}
	$url .= ' href="' . $url . '"';
	
	$output = '<a' . $url . ' class="button '. $style;
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
  * [alert style="(alert, success, secondary)" close="(true false)"]This is an alert[/alert]
  * or
  * [alert text="This is an alert."]
  */
function shortcode_alert( $atts, $content = null ) {
	extract( shortcode_atts( array(
	'style' => '  ', /* alert, success, secondary */
	'close' => 'true', /* display close link */
	'text' => '', 
	), $atts ) );
	
	if($text == '') {
		$text = do_shortcode($content);
	}
	
	$output = '<div data-alert class="alert-box '. $style . '">';
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
	'style' => '', /* callout */
	'close' => 'false', /* display close link */
	'text' => '', 
	), $atts ) );
	
	if($text == ''){
		$text = do_shortcode($content);
	}
	
	$output = '<div class="panel ' . $style . '">';
	$output .= $text;
	$output .= '</div>';
	
	return $output;
}
add_shortcode('panel', 'shortcode_panel');

/**
 * [pricing] shortcode
 *
 * Options: [pricing_info] should have style="title, price, description, bullet-item, bullet, cta-button, or button"
 *
 * Example: [pricing] [pricing_info style="title"]Title[/pricing_info] [pricing_info text="description] [pricing_info style="bullet"]Bullet[/pricing_info] [/pricing]
 */
function shortcode_pricing( $atts, $content = null, $tag ) {
	extract( shortcode_atts( array(
		'style' => 'description',
	), $atts ) );
	if($tag == 'pricing') {
		// create container
		return '<ul class="pricing-table">' . do_shortcode($content) . '</ul>';
	}else {
		// create li
		if($text == ''){
			$text = do_shortcode($content);
		}
		
		$style = ($style == 'bullet' ? 'bullet-item' : $style); // change bullet to bullet-item
		$style = ($style == 'button' ? 'cta-button' : $style); // change button to cta-button
		
		return '<li class="' . $style . '">' . $text . '</li>';
	}
}
add_shortcode('pricing', 'shortcode_pricing');
add_shortcode('pricing_info', 'shortcode_pricing');

/**
 * [tabs] shortcode
 *
 * Creates tabbed content
 *
 * Options: style="auto, vertical-nav, horizontal-nav, accordian
 * 
 * Example:
 * [tabs] [tab title="tab1]Content[/tab] [tab title="tab2"]Content[/tab] [/tabs]
 *
 */
function shortcode_tab( $atts, $content = null, $tag ) {
	extract( shortcode_atts( array(
		'title' => '',
		'style' => 'auto',
		'text' => '',
	), $atts ) );
	
	if($tag == 'tabs') {
		// create container
		if($style != 'auto') {
			$style = ' ' . $style;
			$data = '="' . $style . '"';
		}else {
			$action = '';
		}
		return '<div class="section-container ' . $style . '" data-section' . $action . '>' . do_shortcode($content) . '</div>';
	}else {
		// create tabs
		if($title == '') {
			$title = $atts[0];
		}
		$title = '<p class="title"><a href="#' . $title . '">' . $title . '</a></p>';
		$content = '<div class="content">' . do_shortcode($content) . '</div>';
		return '<section>' . $title . $content . '</section>';
	}
}
add_shortcode('tabs', 'shortcode_tab');
add_shortcode('tab', 'shortcode_tab');

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
		'style' => '',
		'size' => '', 
		), $atts ) );
		
		// get unique modal number 
		$modalNum = substr(uniqid(), -4); //last 4 digits of uniqid will suffice
		if($text == '') {
			$text = do_shortcode($content);
		}
		
		self::$modal .= '<div id="modal-' . $modalNum . '" class="reveal-modal ' . $size . '" role="dialog">';
		self::$modal .= $text . '<a href="#" style="button" class="close-reveal-modal">&times;</a>';
		self::$modal .= '</div>';
		
		if($button != '') {
			$button = '<a href="#" data-reveal-id="modal-' . $modalNum . '" role="button" class="button ' . $style . '">' . $button . '</a>';
		}else {
			self::$modal .= '<script style="text/javascript">
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
add_shortcode('popup', array('fin_modal', 'shortcode_callback'));
for($i=1; $i <= 10; $i++) {
	add_shortcode('modal' . $i, array('fin_modal', 'shortcode_callback'));
	add_shortcode('popup' . $i, array('fin_modal', 'shortcode_callback'));
}
