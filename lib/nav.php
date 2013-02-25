<?php
function fin_create_menus() {
	$customMenus = array('Primary', 'Secondary');
	foreach ($customMenus as $customMenu) {
		$customMenuSlug = strtolower(str_replace('_', ' ', $customMenu));
		
		// Create New Menu
			if(!wp_get_nav_menu_object($customMenuSlug . ' Menu')) {
				$newMenuID = wp_create_nav_menu(ucwords($customMenu) . ' Menu', array('slug' => $customMenuSlug));
				
				// Check if this is the first in $customMenus array
				if(array_search($customMenu, $customMenus) == 0) {				
					// Automatatically add new top-level pages
					// Currently causing errors
//					$options = get_option('nav_menu_options');
//					if(!isset($options['auto_add'])) {
//						$options['auto_add'] = array($newMenuID);
//					}					
//					if(!in_array($newMenuID, $options['auto_add'])) {
//						$options['auto_add'][] = $newMenuID;
//						update_option('nav_menu_options', $options);
//					}
				}
			}
			// Add Menu to assigned location
			$menu = wp_get_nav_menu_object($customMenuSlug . ' Menu');
			
			$locations = get_theme_mod('nav_menu_locations');
			$locations[$customMenuSlug.'_menu'] = $menu->term_id;
			set_theme_mod('nav_menu_locations', $locations);
		}
}
add_action('after_setup_theme','fin_create_menus');

// Register Menu Locations
function fin_register_menus() {
	$customMenus = array('Primary', 'Secondary');
	foreach ($customMenus as $customMenu) {
		$customMenuSlug = strtolower(str_replace('_', ' ', $customMenu));
		// Create Theme Locations
		register_nav_menus(array($customMenuSlug.'_menu' => $customMenu . ' Area'));
	}
}
add_action('after_setup_theme','fin_register_menus');

/**
 * Cleaner walker for wp_nav_menu()
 *
 * Walker_Nav_Menu (WordPress default) example output:
 *   <li id="menu-item-8" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-8"><a href="/">Home</a></li>
 *   <li id="menu-item-9" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-9"><a href="/sample-page/">Sample Page</a></l
 *
 * Custom_Nav_Walker example output:
 *   <li class="menu-home"><a href="/">Home</a></li>
 *   <li class="menu-sample-page"><a href="/sample-page/">Sample Page</a></li>
 */
class Fin_Nav_Walker extends Walker_Nav_Menu {
	function check_current($classes) {
		return preg_match('/(current[-_])|active|dropdown/', $classes);
	}

	function start_lvl(&$output, $depth = 0, $args = array()) {
		$output .= "\n<ul class=\"dropdown-menu\">\n";
	}

	function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
		$item_html = '';
		parent::start_el($item_html, $item, $depth, $args);

		if ($item->is_dropdown && ($depth === 0)) {
			$item_html = str_replace('<a', '<a class="dropdown-toggle" data-toggle="dropdown" data-target="#"', $item_html);
			$item_html = str_replace('</a>', ' <i class="icon-caret-down"></i></a>', $item_html);
		}
		elseif (stristr($item_html, 'li class="divider')) {
			$item_html = preg_replace('/<a[^>]*>.*?<\/a>/iU', '', $item_html);    
		}
		elseif (stristr($item_html, 'li class="nav-header')) {
			$item_html = preg_replace('/<a[^>]*>(.*)<\/a>/iU', '$1', $item_html);
		}   

		$output .= $item_html;
	}

	function display_element($element, &$children_elements, $max_depth, $depth = 0, $args, &$output) {
		$element->is_dropdown = !empty($children_elements[$element->ID]);

		if ($element->is_dropdown) {
			if ($depth === 0) {
				$element->classes[] = 'dropdown';
			} elseif ($depth === 1) {
				$element->classes[] = 'dropdown-submenu';
			}
		}

		parent::display_element($element, $children_elements, $max_depth, $depth, $args, $output);
	}
}

/**
 * Remove the id="" on nav menu items
 * Return 'menu-slug' for nav menu classes
 */
function fin_nav_menu_css_class($classes, $item) {
  $slug = sanitize_title($item->title);
  $classes = preg_replace('/(current(-menu-|[-_]page[-_])(item|parent|ancestor))/', 'active', $classes);
  $classes = preg_replace('/^((menu|page)[-_\w+]+)+/', '', $classes);

  $classes[] = 'menu-' . $slug;

  $classes = array_unique($classes);

  return array_filter($classes, 'is_element_empty');
}
add_filter('nav_menu_css_class', 'fin_nav_menu_css_class', 10, 2);
add_filter('nav_menu_item_id', '__return_null');

/**
 * Clean up wp_nav_menu_args
 *
 * Remove the container
 * Use fin_Nav_Walker() by default
 */
function fin_nav_menu_args($args = '') {
  $fin_nav_menu_args['container'] = false;

  if (!$args['items_wrap']) {
    $fin_nav_menu_args['items_wrap'] = '<ul class="%2$s">%3$s</ul>';
  }

  $fin_nav_menu_args['depth'] = 3;
 
  if (!$args['walker']) {
    $fin_nav_menu_args['walker'] = new fin_Nav_Walker();
  }

  return array_merge($args, $fin_nav_menu_args);
}

add_filter('wp_nav_menu_args', 'fin_nav_menu_args');

/**
 * Add search to nav menu
 *
 */
function fin_add_nav_search($items, $args) {
	$options = get_option('fin_theme_options');
	if($options['include_search']) {
		ob_start();
		get_template_part('templates/searchbar');
		$search = ob_get_contents();
		ob_end_clean();
		$items .= '</ul><ul class="nav pull-right"><li class="search">' . $search . '</li>';
	}
	return $items;
}
add_filter('wp_nav_menu_items', 'fin_add_nav_search', 10, 2);