<?php 
/**
 * Custom post types & taxonomies
 *
 * @link http://codex.wordpress.org/Function_Reference/register_post_type
 * @link http://codex.wordpress.org/Function_Reference/register_taxonomy
 */

/**
 * slideshow custom post type
 */
function register_post_type_slideshow() {
  $labels = array(
    'name'               => 'Slides',
    'singular_name'      => 'Slide',
    'add_new'            => 'Add Slide',
    'add_new_item'       => 'Add New Slide',
    'edit_item'          => 'Edit Slide',
    'new_item'           => 'New Slide',
    'view_item'          => 'View Slide',
    'search_items'       => 'Search Slides',
    'not_found'          => 'No slides found',
    'not_found_in_trash' => 'No slides items found in trash',
    'parent_item_colon'  => '',
    'menu_name'          => 'Slides'
  );

  $args = array(
    'labels'             => $labels,
    'public'             => true,
    'publicly_queryable' => true,
    'show_ui'            => true,
    'show_in_menu'       => true,
    'query_var'          => true,
    'rewrite'            => array('slug' => 'slide'),
    'capability_type'    => 'post',
    'has_archive'        => false,
    'hierarchical'       => false,
    'menu_position'      => null,
    'supports'           => array('title', 'thumbnail', 'excerpt')
  );

  register_post_type('slideshow', $args);
}
add_action('init', 'register_post_type_slideshow');

/**
 * slideshow Location taxonomy
 */
function register_taxonomy_location() {
  $labels = array(
    'name'              => 'Locations',
    'singular_name'     => 'Location',
    'search_items'      => 'Search Locations',
    'all_items'         => 'All Locations',
    'parent_item'       => 'Parent Location',
    'parent_item_colon' => 'Parent Location:',
    'edit_item'         => 'Edit Location',
    'update_item'       => 'Update Location',
    'add_new_item'      => 'Add New Location',
    'new_item_name'     => 'New Location Name',
    'menu_name'         => 'Location'
  );

  $args = array(
    'hierarchical'      => false,
    'labels'            => $labels,
    'show_ui'           => true,
    'show_admin_column' => true,
    'query_var'         => true,
    'rewrite'           => array('slug' => 'slideshow-location'),
  );
  register_taxonomy('slideshow_location', 'slideshow', $args);
}
add_action('init', 'register_taxonomy_location');