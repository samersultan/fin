<?php
/**
 * Remove Default Wordpress Content
 *
 */
function fin_cleanup_default_content() {
	// remove "Sample Page"
	$page = get_page_by_title('Sample Page');
	if($page) {	wp_delete_post($page->ID, true); }
	
	// remove "Hello world!"
	$post = get_page_by_title( 'Hello world!', 'OBJECT', 'post');
	if($post) {	wp_delete_post($post->ID, true); }
}
add_action('after_switch_theme','fin_cleanup_default_content');

/**
 * Replace default category name with 'General'
 *
 */
function fin_cleanup_default_category() {
	$category = get_term_by('id', '1', 'category');
	if($category) {
		$category->name = 'General';
		$category->slug = strtolower(str_replace('_', ' ', 'general'));
	}	
	wp_update_term( $category->term_id, 'category', array( 'slug' => $category->slug, 'name'=> $category->name ) );
}
add_action('after_switch_theme','fin_cleanup_default_category');

/**
 * Remove actions from wp_head
 *
 */
function fin_cleanup_head() {
	remove_action('wp_head', 'feed_links', 2);
	remove_action('wp_head', 'feed_links_extra', 3);
	remove_action('wp_head', 'rsd_link');
	remove_action('wp_head', 'wlwmanifest_link');
	remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
	remove_action('wp_head', 'wp_generator');
	remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);
}
add_action('init', 'fin_cleanup_head');

/**
 * Remove the WordPress version from RSS feeds
 *
 */
add_filter('the_generator', '__return_false');

/**
 * Change Robots.txt
 *
 */
function fin_change_robotstxt($output, $public) {
	$homeURL = get_bloginfo('url');
	$robotstxt = 'Disallow: /wp-content/plugins
Disallow: /wp-content/cache
Disallow: /wp-content/themes
Disallow: /wp-includes/js
Disallow: /feed/
Disallow: /trackback/
Disallow: /rss/
Disallow: /comments/feed/
Disallow: /tag
Disallow: /author
Disallow: /wget/
Disallow: /httpd/
Disallow: /category/*/*
Disallow: */trackback
Disallow: /*?*
Disallow: /*?
Disallow: /*~*
Disallow: /*~

Sitemap: ' . $homeURL . '/sitemap.xml';
	$output .= $robotstxt;
	return $output;
}
add_filter('robots_txt','fin_change_robotstxt', 10, 2);

/**
 * Create sitemap.xml
 *
 */
function fin_create_sitemap() {
  $postsForSitemap = get_posts(array(
    'numberposts' => -1,
    'orderby' => 'modified',
    'post_type'  => array('post','page'),
    'order'    => 'DESC'
  ));
  
  $sitemap = '<?xml version="1.0" encoding="UTF-8"?>';
  $sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
  
  foreach($postsForSitemap as $post) {
    setup_postdata($post);
    
    $postdate = explode(" ", $post->post_modified);
    
    $sitemap .= '<url>'.
      '<loc>'. get_permalink($post->ID) .'</loc>'.
      '<lastmod>'. $postdate[0] .'</lastmod>'.
      '<changefreq>monthly</changefreq>'.
    '</url>';
  }
  
  $sitemap .= '</urlset>';
  
  $fp = fopen(ABSPATH . "sitemap.xml", 'w');
  fwrite($fp, $sitemap);
  fclose($fp);
}
add_action("publish_post", "fin_create_sitemap");
add_action("publish_page", "fin_create_sitemap");

/**
 * Remove silly inline style
 *
 */
function fin_remove_recent_comments_style() {
	global $wp_widget_factory;
	remove_action( 'wp_head', array( $wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style' ) );
}
add_action( 'widgets_init', 'fin_remove_recent_comments_style' );

/**
 * Fix Canonical Links
 *
 */
function fin_rel_canonical() {
	global $wp_the_query;
	
  if (!is_singular()) {
    return;
  }

  if (!$id = $wp_the_query->get_queried_object_id()) {
    return;
  }

  $link = get_permalink($id);
  echo "\t<link rel=\"canonical\" href=\"$link\">\n";
}
remove_action('wp_head', 'rel_canonical');
add_action('wp_head', 'fin_rel_canonical');

/**
 * Clean up output of stylesheet <link> tags
 */
function fin_clean_style_tag($input) {
  preg_match_all("!<link rel='stylesheet'\s?(id='[^']+')?\s+href='(.*)' type='text/css' media='(.*)' />!", $input, $matches);
  // Only display media if it's print
  $media = $matches[3][0] === 'print' ? ' media="print"' : '';
  return '<link rel="stylesheet" href="' . $matches[2][0] . '"' . $media . '>' . "\n";
}
add_filter('style_loader_tag', 'fin_clean_style_tag');

/**
 * Add and remove body_class() classes
 */
function fin_body_class($classes) {
  // Add post/page slug
  if (is_single() || is_page() && !is_front_page()) {
    $classes[] = basename(get_permalink());
  }

  // Remove unnecessary classes
  $home_id_class = 'page-id-' . get_option('page_on_front');
  $remove_classes = array(
    'page-template-default',
    $home_id_class
  );
  $classes = array_diff($classes, $remove_classes);

  return $classes;
}
add_filter('body_class', 'fin_body_class');

/**
 * Redirects search results from /?s=query to /search/query/, converts %20 to +
 *
 * @link http://txfx.net/wordpress-plugins/nice-search/
 */
function fin_nice_search_redirect() {
  if (is_search() && strpos($_SERVER['REQUEST_URI'], '/wp-admin/') === false && strpos($_SERVER['REQUEST_URI'], '/search/') === false) {
    wp_redirect(home_url('/search/' . str_replace(array(' ', '%20'), array('+', '+'), urlencode(get_query_var('s')))), 301);
    exit();
  }
}

add_action('template_redirect', 'fin_nice_search_redirect');

/**
 * Fix for get_search_query() returning +'s between search terms
 */
function fin_search_query($escaped = true) {
  $query = apply_filters('fin_search_query', get_query_var('s'));
  if ($escaped) {
    $query = esc_attr($query);
  }
  return urldecode($query);
}
add_filter('get_search_query', 'fin_search_query');

/**
 * Fix for empty search queries redirecting to home page
 *
 * @link http://wordpress.org/support/topic/blank-search-sends-you-to-the-homepage#post-1772565
 * @link http://core.trac.wordpress.org/ticket/11330
 */
function fin_request_filter($query_vars) {
  if (isset($_GET['s']) && empty($_GET['s'])) {
    $query_vars['s'] = ' ';
  }

  return $query_vars;
}
add_filter('request', 'fin_request_filter');

/**
 * Tell WordPress to use searchform.php from the templates/ directory
 */
function fin_get_search_form() {
  locate_template('/templates/searchform.php', true, true);
}
add_filter('get_search_form', 'fin_get_search_form');

/**
 * Remove unnecessary self-closing tags
 */
function fin_remove_self_closing_tags($input) {
  return str_replace(' />', '>', $input);
}
add_filter('get_avatar',          'fin_remove_self_closing_tags'); // <img />
add_filter('comment_id_fields',   'fin_remove_self_closing_tags'); // <input />
add_filter('post_thumbnail_html', 'fin_remove_self_closing_tags'); // <img />

/**
 * Removes "Protected: " from post titles
 *
 */
function fin_protected_title_format($title) {
       return '%s';
}
add_filter('protected_title_format', 'fin_protected_title_format');

/**
 * Disable Login Error Display
 * Security Fix
 *
 */
add_filter('login_errors',create_function('$a', 'return null;'));

/**
 * change the logo link from wordpress.org to your site
 *
 */
function fin_change_login_url() { return get_bloginfo('url'); }
add_filter('login_headerurl', 'fin_change_login_url');

/**
 * change the alt text on the logo to show your site name 
 *
 */
function fin_change_login_title() { return get_option('blogname'); }
add_filter('login_headertitle', 'fin_change_login_title');

/**
 * change the default edit_post_link() 
 *
 */
function fin_edit_post_link($output) {
 $output = str_replace('class="post-edit-link"', 'class="post-edit-link btn btn-mini btn-info"', $output);
 return $output;
}
add_filter('edit_post_link', 'fin_edit_post_link');

/**
 * change the default edit_comment_link() 
 *
 */
function fin_edit_comment_link($output) {
$output = str_replace('class="comment-edit-link"', 'class="comment-edit-link btn btn-mini btn-info"', $output);
 return $output;
}
add_filter('edit_comment_link', 'fin_edit_comment_link');

/**
 * change the default comment_reply_link() 
 *
 */
function fin_comment_reply_link($output) {
$output = str_replace("class='comment-reply-link'", "class='comment-reply-link btn btn-mini btn-primary btn-block'", $output);
 return $output;
}
add_filter('comment_reply_link', 'fin_comment_reply_link');

/**
 * Add classes to pagination links
 * 
 */
function fin_change_previous_posts_link_attributes() {
    return 'class="pull-left btn btn-tiny" rel="prev"';
}
add_filter('previous_posts_link_attributes', 'fin_change_previous_posts_link_attributes');

function fin_change_next_posts_link_attributes() {
    return 'class="pull-right btn btn-tiny"  rel="next"';
}
add_filter('next_posts_link_attributes', 'fin_change_next_posts_link_attributes');

function fin_change_previous_post_link($link) {
	$link = str_replace('href=', 'class="pull-right btn btn-tiny" href=', $link);
	return $link;
}
add_filter('previous_post_link', 'fin_change_previous_post_link');

function fin_change_next_post_link($link) {
	$link = str_replace('href=', 'class="pull-left btn btn-tiny" href=', $link);
	return $link;
}
add_filter('next_post_link', 'fin_change_next_post_link');
 