<?php
if ( !function_exists( 'optionsframework_init' ) ) {
	define( 'OPTIONS_FRAMEWORK_DIRECTORY', get_template_directory_uri() . '/lib/options/' );
	require_once dirname( __FILE__ ) . '/options/options-framework.php';
}

/**
 * A unique identifier is defined to store the options in the database and reference them from the theme.
 * By default it uses the theme name, in lowercase and without spaces, but this can be changed if needed.
 * If the identifier changes, it'll appear as if the options have been reset.
 */

function optionsframework_option_name() {

	// This gets the theme name from the stylesheet
	$themename = get_option( 'stylesheet' );
	$themename = preg_replace('/\W/', '_', strtolower($themename) );

	$optionsframework_settings = get_option( 'optionsframework' );
	$optionsframework_settings['id'] = $themename;
	update_option( 'optionsframework', $optionsframework_settings );
}

/**
 * Defines an array of options that will be used to generate the settings page and be saved in the database.
 * When creating the 'id' fields, make sure to use all lowercase and no spaces.
 *
 * If you are making your theme translatable, you should replace 'fin'
 * with the actual text domain for your theme.  Read more:
 * http://codex.wordpress.org/Function_Reference/load_theme_textdomain
 */

function optionsframework_options() {
	// Typography Defaults
	$typography_defaults = array(
		'size' => '15px',
		'face' => 'georgia',
		'style' => 'bold',
		'color' => '#bada55' );
		
	// Typography Options
	$font_faces = array(
		'' => '*Default',
		'Arvo, serif' => 'Arvo',
    'Copse, sans-serif' => 'Copse',
    'Droid Sans, sans-serif' => 'Droid Sans',
    'Droid Serif, serif' => 'Droid Serif',
    'Lobster, cursive' => 'Lobster',
    'Nobile, sans-serif' => 'Nobile',
    'Open Sans, sans-serif' => 'Open Sans',
    'Oswald, sans-serif' => 'Oswald',
    'Pacifico, cursive' => 'Pacifico',
    'Rokkitt, serif' => 'Rokkit',
    'PT Sans, sans-serif' => 'PT Sans',
    'Quattrocento, serif' => 'Quattrocento',
    'Raleway, cursive' => 'Raleway',
    'Ubuntu, sans-serif' => 'Ubuntu',
    'Yanone Kaffeesatz, sans-serif' => 'Yanone Kaffeesatz'
	);
	$defaultFace = '';
		
	// If using image radio buttons, define a directory path
	$imagepath =  get_template_directory_uri() . '/assets/img/';
	
	// Background Defaults
	$background_defaults = array(
		'color' => '',
		'image' => '',
		'repeat' => 'repeat',
		'position' => 'top center',
		'attachment'=>'scroll'
	);

	$options = array();

	$options[] = array(
		'name' => 'Typography',
		'type' => 'heading'
	);

	$options[] = array(
		'name' => 'Headings',
		'desc' => 'Font used for headers',
		'id' => 'heading_typography',
		'std' => '',
		'type' => 'select',
		'options' => $font_faces
	);
	
	$options[] = array(
		'name' => 'Main',
		'desc' => 'Font used main text',
		'id' => 'main_typography',
		'std' => $defaultFace,
		'type' => 'select',
		'options' => $font_faces
	);		
							
	$options[] = array(
		'name' => 'Colors',
		'type' => 'heading'
	);
	
	$options[] = array(
		'name' => 'Headings Text Color',
		'desc' => 'Default used if no color is selected.',
		'id' => 'heading_color',
		'std' => '',
		'type' => 'color'
	);
	
	$options[] = array(
		'name' => 'Main Text Color',
		'desc' => 'Default used if no color is selected.',
		'id' => 'main_color',
		'std' => '',
		'type' => 'color'
	);
									
	$options[] = array(
		'name' => 'Link Color',
		'desc' => 'Default used if no color is selected.',
		'id' => 'link_color',
		'std' => '',
		'type' => 'color'
	);

	$options[] = array(
		'name' => 'Navbar Color',
		'desc' => 'Background Color for Navbar',
		'id' => 'top_bar_bg_color',
		'std' => '',
		'type' => 'color'
	);

	$options[] = array(
		'name' => 'Nav Background Color',
		'desc' => 'Background color.',
		'id' => 'top_nav_bg_color',
		'std' => '',
		'type' => 'color'
	);

	$options[] = array(
		'name' => 'Nav Text Color',
		'desc' => 'Link color.',
		'id' => 'top_nav_link_color',
		'std' => '',
		'type' => 'color'
	);

	$options[] = array(
		'name' => 'Nav Background Hover Color',
		'desc' => 'Background hover color.',
		'id' => 'top_nav_hover_bg_color',
		'std' => '',
		'type' => 'color'
	);

	$options[] = array(
		'name' => 'Nav Text Hover Color',
		'desc' => 'Link hover color.',
		'id' => 'top_nav_link_hover_color',
		'std' => '',
		'type' => 'color'
	);

		
	$options[] = array(
		'name' => 'Backgrounds',
		'type' => 'heading'
	);

	$options[] = array(
		'name' => 'Main Background',
		'desc' => 'Main Background image or color.',
		'id' => 'main_background',
		'std' => $background_defaults,
		'type' => 'background'
	);

	$options[] = array(
		'name' => 'Content Background',
		'desc' => 'Background image or color for content areas',
		'id' => 'content_background',
		'std' => $background_defaults,
		'type' => 'background'
	);
								
	$options[] = array(
		'name' => 'Other Settings',
		'type' => 'heading'
	);
							
	$options[] = array(
		'name' => '"Comments are closed" message on pages',
		'desc' => 'Suppress "Comments are closed" message',
		'id' => 'suppress_comments_message',
		'std' => '1',
		'type' => 'checkbox'
	);
	
	$options[] = array(
		'name' => 'Google Analytics',
		'desc' => 'Input your Google Analytics Profile Number',
		'id' => 'analytics',
		'std' => 'UA-#######-#',
		'type' => 'text'
	);

	return $options;
}

/**
 * Add relevant styles to wp_head
 *
 */
function of_add_styles() {
	$options = '';
	
	$heading_typography = of_get_option('heading_typography');
	if($heading_typography) {
		$options .= "h1,h2,h3,h4,h5,h6 { font-family: $heading_typography; }";
	}
	
	$main_typography = of_get_option('main_typography');
	if($main_typography) {
		$options .= "body,div,dl,dt,dd,ul,ol,li,pre,form,p,blockquote,th,td { font-family: $main_typography; }";
	}
	
	$headings_color = of_get_option('headings_color');
	if($headings_color) {
		$options .= "h1,h2,h3,h4,h5,h6 { color: $headings_color; }";
	}
	
	$main_color = of_get_option('main_color');
	if($main_color) {
		$options .= "body,div,dl,dt,dd,ul,ol,li,pre,form,p,blockquote,th,td { color: $main_color; }";
	}
	
	$link_color = of_get_option('link_color');
	if($link_color) {
		$options .= "a { color: $link_color;}
		a:hover {color: " . brightness($link_color, .25) . "; }
		a:visited { color: " . brightness($link_color, -.25) . "; }";
	}
	
	$top_nav_bg_color = of_get_option('top_nav_bg_color');
	if($top_nav_bg_color) {
		$options .= "#nav-main { background: $top_nav_bg_color; }";
	}
	
	$top_nav_link_color = of_get_option('top_nav_link_color');
	if($top_nav_link_color) {
		$options .= "#nav-main a { color: $top_nav_link_color; }";
	}
	
	$top_nav_hover_bg_color = of_get_option('top_nav_hover_bg_color');
	if($top_nav_hover_bg_color) {
		$options .= "#nav-main a:hover { background: $top_nav_bg_color; }";
	}
	
	$top_nav_link_hover_color = of_get_option('top_nav_link_hover_color');
	if($top_nav_link_hover_color) {
		$options .= "#nav-main a:hover { color: $top_nav_link_color; }";
	}
	
	$main_background = of_get_option('main_background');
	if($main_background) {
		if($main_background['image']) {
			$options .= "body { background:url(" . $main_background['image'] . "); }";
		}elseif($main_background['image']) {
			$options .= "body { background-color:" . $main_background['color'] . ";}";
		}	
	}
	
	$content_background = of_get_option('content_background');
	if($content_background) {
		if($content_background['image']) {
			$options .= "#content { background:url(" . $content_background['image'] . "); }";
		}elseif($content_background['image']) {
			$options .= "#content { background-color:" . $content_background['color'] . ";}";
		}	
	}
	
	if($options != '') {
		echo '<style>' . $options . '<style>';
	}
}
add_action('wp_head', 'of_add_styles');

/**
 * Helper Functions
 *
 */
// Pull all the categories into an array
function of_get_categories() {
	$options_categories = array();
	$options_categories_obj = get_categories();
	foreach ($options_categories_obj as $category) {
		$options_categories[$category->cat_ID] = $category->cat_name;
	}
	return $options_categories;
}	

// Pull all tags into an array
function of_get_tags() {
	$options_tags = array();
	$options_tags_obj = get_tags();
	foreach ( $options_tags_obj as $tag ) {
		$options_tags[$tag->term_id] = $tag->name;
	}
	return $options_tags;
}

// Pull all the pages into an array
function of_get_pages() {
	$options_pages = array();
	$options_pages_obj = get_pages('sort_column=post_parent,menu_order'
	);

	$options_pages[''] = 'Select a page:';
	foreach ($options_pages_obj as $page) {
		$options_pages[$page->ID] = $page->post_title;
	}
	return $options_pages;
}