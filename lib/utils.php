<?php
/**
 * Theme Wrapper
 *
 * @link http://scribu.net/wordpress/theme-wrappers.html
 */

function fin_template_path() {
  return Fin_Wrapping::$main_template;
}

class Fin_Wrapping {

  // Stores the full path to the main template file
  static $main_template;

  // Stores the base name of the template file; e.g. 'page' for 'page.php' etc.
  static $base;

  static function wrap($template) {
    self::$main_template = $template;

    self::$base = substr(basename(self::$main_template), 0, -4);

    if (self::$base === 'index') {
      self::$base = false;
    }

    $templates = array('base.php');

    if (self::$base) {
      array_unshift($templates, sprintf('base-%s.php', self::$base ));
    }

    return locate_template($templates);
  }
}
add_filter('template_include', array('Fin_Wrapping', 'wrap'), 99);


// returns WordPress subdirectory if applicable
function wp_base_dir() {
  preg_match('!(https?://[^/|"]+)([^"]+)?!', site_url(), $matches);
  if (count($matches) === 3) {
    return end($matches);
  } else {
    return '';
  }
}

// opposite of built in WP functions for trailing slashes
function leadingslashit($string) {
  return '/' . unleadingslashit($string);
}

function unleadingslashit($string) {
  return ltrim($string, '/');
}

function add_filters($tags, $function) {
  foreach($tags as $tag) {
    add_filter($tag, $function);
  }
}

function is_element_empty($element) {
  $element = trim($element);
  return empty($element) ? false : true;
}

// Get the number of images in a gallery post
function get_image_count($ID) {
	$images = get_children(array(
		'post_parent'=>$ID,
		'post_type'=>'attachment',
		'post_mime_type'=>'image',
		'orderby'=>'menu_order',
		'order' => 'ASC',
		'numberposts' => 999));
	return count($images);
}

// Exif Function for images
function get_exif($att) {
	$imgmeta = wp_get_attachment_metadata($att);
	if($imgmeta) { // Check for Bad Data
		if($imgmeta['image_meta']['focal_length'] == 0
		|| $imgmeta['image_meta']['aperture'] == 0
		|| $imgmeta['image_meta']['shutter_speed'] == 0
		|| $imgmeta['image_meta']['iso'] == 0) {
			$output = '';
		}else { // Convert the shutter speed retrieve from database to fraction
			if ((1 / $imgmeta['image_meta']['shutter_speed']) > 1) {
				if ((number_format((1 / $imgmeta['image_meta']['shutter_speed']), 1)) == 1.3
				|| number_format((1 / $imgmeta['image_meta']['shutter_speed']), 1) == 1.5
				|| number_format((1 / $imgmeta['image_meta']['shutter_speed']), 1) == 1.6
				|| number_format((1 / $imgmeta['image_meta']['shutter_speed']), 1) == 2.5) {
					$pshutter = "1/" . number_format((1 / $imgmeta['image_meta']['shutter_speed']), 1, '.', '') . " second";
				} else {
					$pshutter = "1/" . number_format((1 / $imgmeta['image_meta']['shutter_speed']), 0, '.', '') . " second";
				}
			} else {
				$pshutter = $imgmeta['image_meta']['shutter_speed'] . " seconds";
			}
			// Create Output
			$output = '<ul>';
				$output .= '<li><time datetime="' . date('c', $imgmeta['image_meta']['created_timestamp']) . '"><span class="month">' . date('F', $imgmeta['image_meta']['created_timestamp']).'</span> <span class="day">'.date('j', $imgmeta['image_meta']['created_timestamp']) . '</span><span class="suffix">' . date('S', $imgmeta['image_meta']['created_timestamp']) . '</span> <span class="year">' . date('Y', $imgmeta['image_meta']['created_timestamp']) . '</span></time></li>';
				$output .= '<li>' . $imgmeta['image_meta']['camera'] . '</li>';
				$output .= '<li>' . $imgmeta['image_meta']['focal_length'] . 'mm</li>';
				$output .= '<li><span style="font-style:italic;font-family: Trebuchet MS,Candara,Georgia; text-transform:lowercase">f</span>/' . $imgmeta['image_meta']['aperture'] . '</li>';
				$output .= '<li>'. $pshutter .'</li>';
				$output .= '<li>'. $imgmeta['image_meta']['iso'] .' ISO</li>';
			$output .= '</ul>';
		}
	}else { // No Data Found
		$output = '';
	}
	return $output;
}

function is_parent_category($cat = null) {
	if(is_numeric($cat)) {
		$category = get_the_category_by_ID($cat);
	}elseif (is_string($cat)) {
		$category = get_category_by_slug($cat);
	}elseif (is_null($cat))  {
		$category = get_queried_object()  ;
	}
	$children = get_categories("parent={$category->term_id}");
	if(!empty($children)) {
		return true;
	}else {
		return false;
	}
}

function is_child_category($cat = null) {
	if(is_numeric($cat)) {
		$category = get_the_category_by_ID($cat);
	}elseif (is_string($cat)) {
		$category = get_category_by_slug($cat);
	}elseif (is_null($cat))  {
		$category = get_queried_object()  ;
	}
	if($category->parent != 0) {
		return true;
	}else {
		return false;
	}
}

// Adds a page/post item to one of the menus
function fin_add_to_menu($itemID, $menu) {
	// hack - get menu based on location
	// can't get wp_get_nav_menu_object to recognize slug or name for some reason though.
	$locations = get_nav_menu_locations();
	$menuObj = wp_get_nav_menu_object($locations[$menu]);
	$menuID = (int) $menuObj->term_id;
	$menuItems = wp_get_nav_menu_items($menuID);
	$itemTitle = get_the_title($itemID);
	$itemType = get_post_type($itemID);
	$itemObj = array(
		'menu-item-title' => $itemTitle,
		'menu-item-object-id' => $itemID,
		'menu-item-object' => $itemType,
		'menu-item-type' => 'post_type',
		'menu-item-status' => 'publish'
	);
	wp_update_nav_menu_item($menuID,0, $itemObj);
}
