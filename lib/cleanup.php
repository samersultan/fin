<?php
/**
 * Change WordPress wpautop into something more user-friendly
 *
 */
function fin_clean_pre($matches) {
  if ( is_array($matches) ) {
    $text = $matches[1] . $matches[2] . "</pre>";
  }else {
    $text = $matches;
  }
  $text = str_replace('<br />', '', $text); // I love this line, Geshi adds too many <br /> tags
  // $text = str_replace('<p>', "\n", $text);
  // $text = str_replace('</p>', '', $text);
  return $text;
}

function fin_wpautop($pee, $br = 0) {
  if ( trim($pee) === '' )
    return '';
  $pee = $pee . "\n"; // just to make things a little easier, pad the end
  $pee = preg_replace('|<br />\s*<br />|', "\n\n", $pee);
  // Space things out a little
  $allblocks = '(?:table|thead|tfoot|caption|col|colgroup|tbody|tr|td|th|div|dl|dd|dt|ul|ol|li|select|option|form|map|area|blockquote|address|math|style|input|p|h[1-6]|hr|fieldset|legend|section|article|aside|header|footer|nav|figure|figcaption|details|menu|summary)';
  $pee = preg_replace('~<p>\s*<(' . $allblocks . ')\b~i', '<$1', $pee);
	$pee = preg_replace('~</(' . $allblocks . ')>\s*</p>~i', '</$1>', $pee);
  $pee = str_replace(array("\r\n", "\r"), "\n", $pee); // cross-platform newlines
  if ( strpos($pee, '<object') !== false ) {
    $pee = preg_replace('|\s*<param([^>]*)>\s*|', "<param$1>", $pee); // no pee inside object/embed
    $pee = preg_replace('|\s*</embed>\s*|', '</embed>', $pee);
  }
  $pee = preg_replace("/\n\n+/", "\n\n", $pee); // take care of duplicates
  // make paragraphs, including one at the end
  $pees = preg_split('/\n\s*\n/', $pee, -1, PREG_SPLIT_NO_EMPTY);
  $pee = '';
  foreach ( $pees as $tinkle )
    $pee .= '<p>' . trim($tinkle, "\n") . "</p>\n";
  $pee = preg_replace('|<p>\s*</p>|', '', $pee); // under certain strange conditions it could create a P of entirely whitespace
  $pee = preg_replace('!<p>([^<]+)</(div|address|form)>!', "<p>$1</p></$2>", $pee);
  $pee = preg_replace('!<p>\s*(</?' . $allblocks . '[^>]*>)\s*</p>!', "$1", $pee); // don't pee all over a tag
  $pee = preg_replace("|<p>(<li.+?)</p>|", "$1", $pee); // problem with nested lists
  $pee = preg_replace('|<p><blockquote([^>]*)>|i', "<blockquote$1><p>", $pee);
  $pee = str_replace('</blockquote></p>', '</p></blockquote>', $pee);
  $pee = preg_replace('!<p>\s*(</?' . $allblocks . '[^>]*>)!', "$1", $pee);
  $pee = preg_replace('!(</?' . $allblocks . '[^>]*>)\s*</p>!', "$1", $pee);
  if ($br) {
    $pee = preg_replace_callback('/<(script|style).*?<\/\\1>/s', '_autop_newline_preservation_helper', $pee);
    $pee = preg_replace('|(?<!<br />)\s*\n|', "<br />\n", $pee); // optionally make line breaks
    $pee = str_replace('<WPPreserveNewline />', "\n", $pee);
  }
  $pee = preg_replace('!(</?' . $allblocks . '[^>]*>)\s*<br />!', "$1", $pee);
  $pee = preg_replace('!<br />(\s*</?(?:p|li|div|dl|dd|dt|th|pre|td|ul|ol)[^>]*>)!', '$1', $pee);
  if (strpos($pee, '<pre') !== false)
    $pee = preg_replace_callback('!(<pre[^>]*>)(.*?)</pre>!is', 'fin_clean_pre', $pee );
  $pee = preg_replace( "|\n</p>$|", '</p>', $pee );

	// wrap imageas in a <figure> instead of P
	$pee = preg_replace('/<p>\\s*?(<a .*?><img.*?><\\/a>|<img.*?>)?\\s*<\\/p>/s', '<div class="figure">$1</div>', $pee);
	
  return $pee;
}
// Remove wpautop filters
//remove_filter( 'the_content', 'wpautop' );
//remove_filter( 'the_excerpt', 'wpautop' );
// Insert custom wpautop filters after do_shortcode calls
add_filter( 'the_content', 'fin_wpautop', 99 );
add_filter( 'the_excerpt', 'fin_wpautop', 99 );

/**
 * Strip shortcodes out of the_excerpt but keep content
 *
 */
function custom_excerpt($the_content = '') {
	$raw_excerpt = $the_content;
	if ( '' == $the_content ) {
		$the_content = get_the_content();
		$the_content = preg_replace("~(?:\[/?)[^/\]]+/?\]~s", '', $the_content);  # strip shortcodes, keep shortcode content		
		$excerpt_length = apply_filters('excerpt_length', 55);
		$excerpt_more = apply_filters('excerpt_more', ' ' . '[...]');
		$the_content = wp_trim_words( $the_content, $excerpt_length, $excerpt_more );
	}
	return apply_filters('wp_trim_excerpt', $the_content, $raw_excerpt);
}
remove_filter( 'get_the_excerpt', 'wp_trim_excerpt'  );
add_filter( 'get_the_excerpt', 'custom_excerpt'  );

/**
 * Clean up inserted images
 *
 */
function fin_cleanup_images($html) {
	$html = preg_replace( '/(width|height)=\"\d*\"\s/', "", $html );
	return $html;
}
add_filter('post_thumbnail_html', 'fin_cleanup_images', 10);
add_filter('image_send_to_editor', 'fin_cleanup_images', 10);
add_filter('the_content', 'fin_cleanup_images', 10);

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
function fin_change_login_url() {
	return get_bloginfo('url');
}
add_filter('login_headerurl', 'fin_change_login_url');

/**
 * change the alt text on the logo to show your site name 
 *
 */
function fin_change_login_title() {
	return get_option('blogname');
}
add_filter('login_headertitle', 'fin_change_login_title');

/**
 * change the default edit_post_link() 
 *
 */
function fin_edit_post_link($output) {
	$output = str_replace('class="post-edit-link"', 'class="meta-edit button tiny secondary"', $output);
	return $output;
}
add_filter('edit_post_link', 'fin_edit_post_link');

/**
 * change the default edit_comment_link() 
 *
 */
function fin_edit_comment_link($output) {
	$output = str_replace('class="comment-edit-link"', 'class="meta-edit button tiny secondary"', $output);
	return $output;
}
add_filter('edit_comment_link', 'fin_edit_comment_link');

/**
 * change the default comment_reply_link() 
 *
 */
function fin_comment_reply_link($output) {
	$output = str_replace("class='comment-reply-link'", "class='comment-reply-link button small'", $output);
	return $output;
}
add_filter('comment_reply_link', 'fin_comment_reply_link');

/**
 * change cancel_comment_reply_link()
 *
 */
function fin_cancel_comment_reply_link($reply_link) {
	$output = str_replace('<a rel="nofollow" id="cancel-comment-reply-link"', '<a rel="nofollow" id="cancel-comment-reply-link" class="close"', $reply_link);
	
	return $output;
}
add_filter('cancel_comment_reply_link','fin_cancel_comment_reply_link');

/**
 * Add classes to pagination links
 * 
 */
function fin_change_previous_posts_link_attributes() {
    return 'class="left button small" rel="prev"';
}
add_filter('previous_posts_link_attributes', 'fin_change_previous_posts_link_attributes');

function fin_change_next_posts_link_attributes() {
    return 'class="right button small"  rel="next"';
}
add_filter('next_posts_link_attributes', 'fin_change_next_posts_link_attributes');

function fin_change_previous_post_link($link) {
	$link = str_replace('href=', 'class="right button small" href=', $link);
	return $link;
}
add_filter('previous_post_link', 'fin_change_previous_post_link');

function fin_change_next_post_link($link) {
	$link = str_replace('href=', 'class="left button small" href=', $link);
	return $link;
}
add_filter('next_post_link', 'fin_change_next_post_link');

/**
 * Add a spam-trap to comment form and registration form
 *
 * Include a hidden field called name and set it to hidden. If it receives an input, we have a bot!
 */
function get_decoy_fields() {
	$decoys = array( 'firstname', 'lastname', 'email2', 'address', 'address2', 'city', 'state', 'zipcode', 'telephone', 'phone');
	return $decoys;
}
function get_dailyID() {
	srand(date('Ymd'));
	$number = rand(0,9999999);
	$hash = substr(sha1($number),0,8);
	return $hash;
}

function fin_add_comment_spam_trap($arg) {
	$decoyFields = get_decoy_fields();
	$arg['fields'] = array_reverse($arg['fields'], true); //reverse order to place decoys at front of form.
	
	$hash = get_dailyID();
	
	$spamtrap = '';
	foreach ($decoyFields as $decoy) {
		$spamtrap .= '<label class="hide" for="' . $decoy . $hash . '" >' . $decoy . ' *</label><input class="hide" name="' . $decoy . $hash . '" type="text" autocomplete="off">';
	}
	$arg['fields']['spamtrap'] = $spamtrap;
	$arg['fields'] = array_reverse($arg['fields'], true); //reverse back so fields are in regular order
	
	// Add hashes to author and email
	$arg['fields']['author'] = str_replace('name="author"', 'name="author' . $hash . '"', $arg['fields']['author'] );
	$arg['fields']['email'] = str_replace('name="email"', 'name="email' . $hash . '"', $arg['fields']['email'] );
	return $arg;
}
add_filter('comment_form_defaults', 'fin_add_comment_spam_trap');

function fin_add_register_spam_trap() {
	$decoyFields = get_decoy_fields(); // List of names for decoy fields
	$hash = get_dailyID(); // Get unique daily ID hash
	
	$output = '';
	foreach ($decoyFields as $decoy) {
		$output .= '<p class="hide"><label for="' . $decoy . $hash . '" >' . $decoy . ' *</label><input name="' . $decoy . $hash . '" type="text" autocomplete="off"></p>';
	}
	echo $output;
}
add_action('register_form', 'fin_add_register_spam_trap'); 

function fin_fix_hashed_comment($commentdata) {
	$hash = get_dailyID();
	
	// fix hashed author & email fields
	if(isset($_POST['author' . $hash])) {
		$_POST['author'] = trim(strip_tags($_POST['author' . $hash]));
	}
	if(isset($_POST['email' . $hash])) {
		$_POST['email'] = trim(strip_tags($_POST['email' . $hash]));
	}
	return $commentdata;
}
add_action('pre_comment_on_post', 'fin_fix_hashed_comment');

function fin_check_comment_spam_trap($comment_id, $approved) {
	// first check http_referer
	$siteURL = str_ireplace('www.', '', parse_url(get_bloginfo('url'), PHP_URL_HOST));
	if(!stripos($_SERVER['HTTP_REFERER'], $siteURL)) {
		wp_die('There was an error.', 'Error');
		exit;
	}
	if($approved != 'spam') { // No need to check twice
		$decoyFields = get_decoy_fields();
		$hash = get_dailyID();
		
		foreach ($decoyFields as $decoy) {
			if(isset($_POST[$decoy . $hash])) {
				wp_spam_comment($comment_id);
			}
		}
	}
}
add_action('comment_post', 'fin_check_comment_spam_trap');

function fin_check_register_spam_trap($errors, $sanitized_user_login, $user_email) {
	// first check http_referer
	$siteURL = str_ireplace('www.', '', parse_url(get_bloginfo('url'), PHP_URL_HOST));
	if(!stripos($_SERVER['HTTP_REFERER'], $siteURL)) {
		wp_die('There was an error.', 'Error');
		exit;
	}
	if(!$errors->get_error_code()) { // Check to see if there are already errors
		$decoyFields = get_decoy_fields(); // List of names for decoy fields
		$hash = get_dailyID(); // Get unique daily ID hash
		
		foreach ($decoyFields as $decoy) {
			if(isset($_POST[$decoy . $hash])) {
				wp_die('There was an error. Sorry', 'Error');
				exit;
			}
		}
	}
	return $errors;
}
add_action('registration_errors', 'fin_check_register_spam_trap', 10, 3);