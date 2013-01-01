<!doctype html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" <?php language_attributes(); ?>> <![endif]-->
<!--[if gt IE 8]> <html class="no-js" <?php language_attributes(); ?>> <![endif]-->
<head>
	<meta charset="utf-8">
	
	<title><?php bloginfo( 'name' );
	wp_title( '|', true, 'left' );
	if(get_bloginfo('description','Display') && (is_home() || is_front_page() || is_author())){
			echo ' | ' . get_bloginfo('description', 'Display');
	}
	// Add a page number if necessary:
	global $page, $paged;
	if ( $paged >= 2 || $page >= 2 )
		echo ' | ' . sprintf( __( 'Page %s'), max($paged, $page));
	?></title>
	
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
		
	<?php // Only include rss if there are posts
	if (wp_count_posts()->publish > 0) { ?>
	<link rel="alternate" type="application/rss+xml" title="<?php echo get_bloginfo('name'); ?> Feed" href="<?php echo home_url(); ?>/feed/">
	<?php } ?>
		
	<?php wp_head(); ?>
	
</head>