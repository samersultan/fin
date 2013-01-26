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
  return get_template_part('templates/rotator', $location);
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

  $output = '<ul class="block-grid four-up gallery">';

  $i = 0;
  foreach ($attachments as $id => $attachment) {
    $link = isset($attr['link']) && 'file' == $attr['link'] ? wp_get_attachment_link($id, $size, false, false) : wp_get_attachment_link($id, $size, true, false);

    $output .= '<li>' . $link;
    if (trim($attachment->post_excerpt)) {
      $output .= '<div class="caption hidden">' . wptexturize($attachment->post_excerpt) . '</div>';
    }
    $output .= '</li>';
  }

  $output .= '</ul>';

  return $output;
}
add_shortcode('gallery', 'shortcode_gallery');

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
  * [column span="(one, two, three... one-third, three-fourths...)" offset="(one, two, three...)" centered="(true, false)"][/column]
  */
 function shortcode_column( $atts, $content = null ) {
     extract( shortcode_atts( array(
         'centered' => '',
         'span' => '',
         'offset' => ''
         ), $atts ) );
  
     // Set the 'center' variable
     if ($centered == 'true') {
         $centered = 'centered';
     }
     if($offset != '') {
         $offset = 'offset-by-' . $offset;
     }
  
     return '<div class="' . esc_attr($span) . ' columns ' . esc_attr($centered) . ' ' . esc_attr($offset) . '">' . do_shortcode($content) . '</div>';
 }
 add_shortcode( 'column', 'shortcode_column' );
 
 /**
  * [button] shortcode
  *
  * Creates a button
  *
  * Example:
  * [button type="(radius round)" size="(small medium large)" type="(primary secondary success alert)" nice="true false" url="http://#"]This is a button[/button]
  * or
  * [button text="This is a button." url="http://#"]
  */
 function shortcode_button( $atts, $content = null ) {
     extract( shortcode_atts( array(
     'type' => 'radius', /* radius, round */
     'size' => 'medium', /* small, medium, large */
     'type' => 'secondary', /* primary, secondary, warning, success, error */
     'nice' => 'false',
     'url'  => '',
     'text' => '', 
     ), $atts ) );
      
     if($text == ''){
         $text = do_shortcode($content);
     }
      
     $output = '<a href="' . $url . '" class="button '. $type . ' ' . $size . ' ' . $color;
     if( $nice == 'true' ){ $output .= ' nice';}
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
  * Example:
  * [alert type="(warning success error)" close="(true false)"]This is an alert[/alert]
  * or
  * [alert text="This is an alert."]
  */
 function shortcode_alert( $atts, $content = null ) {
     extract( shortcode_atts( array(
     'type' => '  ', /* warning, success, error */
     'close' => 'true', /* display close link */
     'text' => '', 
     ), $atts ) );
      
     if($text == ''){
         $text = do_shortcode($content);
     }
      
     $output = '<div class="fade in alert-box '. $type . '">';
     $output .= $text;
     if($close == 'true') {
         $output .= '<a class="close" href="#">×</a></div>';
     }
      
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
     'type' => '  ', /* warning, success, error */
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
 