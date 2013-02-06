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
	    'columns'    => 3,
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
	
	  $output = '<ul class="thumbnails gallery">';
	
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
		'span' => '',
		'offset' => ''
		), $atts ) );
	
	if($span != '') {
		$span = 'span' . $span;
	}
	
	if($offset != '') {
		$offset = ' offset' . $offset;
	}

	return '<div class="' . esc_attr($span) . esc_attr($offset) . '">' . do_shortcode($content) . '</div>';
}
add_shortcode( 'column', 'shortcode_column' );
 
 /**
  * [button] shortcode
  *
  * Creates a button
  *
  * Examples:
  * [button type="(radius round)" size="(mini small large)" type="(primary success info warning danger disabled)" url="http://#"]This is a button[/button]
  * or
  * [button text="This is a button." url="http://#"]
  */
 function shortcode_button( $atts, $content = null ) {
     extract( shortcode_atts( array(
     'type' => 'radius', /* radius, round */
     'size' => 'medium', /* small, medium, large */
     'type' => 'secondary', /* primary, secondary, warning, success, error */
     'url'  => '',
     'text' => '', 
     ), $atts ) );
      
     if($text == ''){
         $text = do_shortcode($content);
     }
      
     $output = '<a href="' . $url . '" class="btn '. $type . ' ' . $size;
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
		
		if($text == '') {
			$text = do_shortcode($content);
		}
		if($type != '') {
			$type = ' alert-' . $type;
		}
		
		$output = '<div class="alert fade in'. $type . '">';
		$output .= $text;
		if($close == 'true') {
			$output .= '<button type="button" class="close" data-dismiss="alert">&times;</button>';
		}
		$output .= '</div>';
		
		return $output;
 }
 add_shortcode('alert', 'shortcode_alert');
  
 /**
  * [well] shortcode
  *
  * Creates a panel
  *
  * Examples:
  * [well]This is well[/well]
  * or
  * [well text="This is a well."]
  */
 function shortcode_well( $atts, $content = null ) {
     extract( shortcode_atts( array(
     'size' => '', /* small large */
     'text' => '', 
     ), $atts ) );
      
     if($size != '') {
     	$size = ' well-'. $size;
     }
     if($text == '') {
         $text = do_shortcode($content);
     }
      
     $output = '<div class="well' . $size . '">';
     $output .= $text;
     $output .= '</div>';
      
     return $output;
 }
 add_shortcode('well', 'shortcode_well');
 
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
function shortcode_modal( $atts, $content = null ) {
	extract( shortcode_atts( array(
	'text' => '',
	'button' => '',
	'size' => '', 
	), $atts ) );
	 
	$modalNum = rand();
	if($text == '') {
	    $text = do_shortcode($content);
	}
	$output = '';
	if($button != '') {
		$output .= '<a href="#modal-' . $modalNum . '" role="button" class="btn" data-toggle="modal">' . $button . '</a>';
	}
	
	$output .= '<div id="modal-' . $modalNum . '" class="modal fade' . $size . '">';
	//$output .= '<div class="modal-header"></div>';
	$output .= '<div class="modal-body"><button type="button" class="close" data-dismiss="modal">&times;</button>' . $text . '</div>';
	$output .= '</div>';
	 
	return $output;
}
 add_shortcode('modal', 'shortcode_modal');