<?php
/**
 * Theme Wrapper
 *
 * @link http://scribu.net/wordpress/theme-wrappers.html
 */

function fin_template_path() {
  return Fin_Wrapping::$main_template;
}

function fin_sidebar_path() {
  return Fin_Wrapping::sidebar();
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
      array_unshift($templates, sprintf('base-%s.php', self::$base));
    }

    return locate_template($templates);
  }

  static function sidebar() {
    $templates = array('templates/sidebar.php');

    if (self::$base) {
      array_unshift($templates, sprintf('templates/sidebar-%s.php', self::$base));
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
// Will output list (default) or button group
function get_exif($att, $separator = '', $before = '', $after = '') {
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
			
			$output = $before;
				$output .=  '<time datetime="' . date('c', $imgmeta['image_meta']['created_timestamp']) . '"><span class="month">' . date('F', $imgmeta['image_meta']['created_timestamp']).'</span> <span class="day">'.date('j', $imgmeta['image_meta']['created_timestamp']) . '</span><span class="suffix">' . date('S', $imgmeta['image_meta']['created_timestamp']) . '</span> <span class="year">' . date('Y', $imgmeta['image_meta']['created_timestamp']) . '</span></time>' . $separator;
				$output .=  $imgmeta['image_meta']['camera'] . $separator;
				$output .=  $imgmeta['image_meta']['focal_length'] . 'mm' . $separator;
				$output .=  '<span style="font-style:italic;font-family: Trebuchet MS,Candara,Georgia; text-transform:lowercase">f</span>/' . $imgmeta['image_meta']['aperture'] . $separator;
				$output .=  $pshutter . $separator;
				$output .=  $imgmeta['image_meta']['iso'] .' ISO';
			$output .= $after;
		}
	}else { // No Data Found
		$output = '';
	}
	return $output;
}

function get_time_ago($postTime) {
	$currentTime = time();
	$timDifference = $currentTime - $postTime;
	
	$minInSecs = 60;
	$hourInSecs = 3600;
	$dayInSecs = 86400;
	$monthInSecs = $dayInSecs * 31;
	$yearInSecs = $dayInSecs * 366;
	
	if($timDifference > ($yearInSecs)) {
		return 'over ' . floor($timDifference/$yearInSecs) . __(' years ago');
	}else {
		return human_time_diff($postTime, $currentTime) . ' ' . __('ago');
	}
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

/**
 * Returns true/false if you are on the wp-login or wp-register pages
 *
 */
function is_login() {
    return in_array( $GLOBALS['pagenow'], array( 'wp-login.php', 'wp-register.php' ) );
}

/**
 * Function to adjust brightness/darkness of a hex value
 *
 */
function brightness($hex, $percent) {
	// Work out if hash given
	$hash = '';
	if (stristr($hex,'#')) {
		$hex = str_replace('#','',$hex);
		$hash = '#';
	}
	/// HEX TO RGB
	$rgb = array(hexdec(substr($hex,0,2)), hexdec(substr($hex,2,2)), hexdec(substr($hex,4,2)));
	//// CALCULATE
	for ($i=0; $i<3; $i++) {
		// See if brighter or darker
		if ($percent > 0) {
			// Lighter
			$rgb[$i] = round($rgb[$i] * $percent) + round(255 * (1-$percent));
		} else {
			// Darker
			$positivePercent = $percent - ($percent*2);
			$rgb[$i] = round($rgb[$i] * $positivePercent) + round(0 * (1-$positivePercent));
		}
		// In case rounding up causes us to go to 256
		if ($rgb[$i] > 255) {
			$rgb[$i] = 255;
		}
	}
	//// RBG to Hex
	$hex = '';
	for($i=0; $i < 3; $i++) {
		// Convert the decimal digit to hex
		$hexDigit = dechex($rgb[$i]);
		// Add a leading zero if necessary
		if(strlen($hexDigit) == 1) {
		$hexDigit = "0" . $hexDigit;
		}
		// Append to the hex string
		$hex .= $hexDigit;
	}
	return $hash.$hex;
}

function fin_get_avatar($comment, $size='64', $placeholder='404') {
	if($size <= "60" && $size >= "34") {
		$finalsize = $size."px;";
		$suffix = "_normal";
	}elseif ($size <= "35" && $size >= "0") {
		$finalsize = $size."px;";
		$suffix = "_mini";
	}elseif ($size <= "90" && $size >= "61") {
		$finalsize = $size."px;";
		$suffix = "_bigger";
	}elseif ($size >= "91") {
		$finalsize = $size."px;";
		$suffix = "";
	}
	
	$twitterImg = false;
	if($comment->user_id) { // check user profile for twitter id
		$user_profile = get_userdata($comment->user_id);
		$comment_author_twitter = $user_profile->twitter;
	}
	
	if($twitterImg !== false) {
		$quaseimg = $result->profile_image_url;  // we get user image from array we built in the process function
		// we need to make some calcs to get the image without _normal.png (we will add .png later and _normal deppending on the choosen size)
		
		$wherestop = strrpos($quaseimg, "_");  
		$lenght = strlen( $quaseimg  );
		$takeof = $lenght-$wherestop;
		$keepit = $lenght-4;
		$fileextension = substr($quaseimg, $keepit, 4);
		// Here we have a complete image url:
		$image = substr($quaseimg, 0, -$takeof).$suffix.$fileextension; 
		
		// Set author name
		$authorName = $result->name;
		
		// If in the url we find static.twitter.com it means is the default img so set result to false
		if(strpos($quaseimg, "static.twitter.com") !== false) {
			$result = false;
		}
	}
	if($twitterImg == false) { // Check Gravatar
		// Create gravatar url using placeholder or 404
		if($placeholder != '404') {
			$placeholder = urlencode($placeholder);
		}
		$image = 'http://www.gravatar.com/avatar/' . md5($comment->comment_author_email) . '?s=' . $size . '&d=' . $placeholder;
		$authorName = $comment->comment_author;
		$headers = get_headers($image);
		if (!strpos($headers[0],'200')) {	// no avatar
			return '';
		}
	}
	
	$avatar = '<img src="' . $image . '" class="avatar" alt="' . $authorName . '">';
	
	$authorURL = $comment->comment_author_url;
	if($authorURL) {
		return '<a href="' . $authorURL . '" rel="external nofollow" class="th">' . $avatar . '</a>';
	}else { // no URL
		return $avatar;
	}
}