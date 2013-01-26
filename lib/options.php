<?php 
/** Customize Front End **/
function fin_customize_register($wp_customize) {
	/**** Backgrounds ****/
	$wp_customize->add_section('fin_backgrounds', array(
		'title'          => __( 'Backgrounds', 'fin' ),
		'priority'       => 100,
	) );
	
	// Main Background
	$wp_customize->add_setting( 'fin_theme_options[main_background]', array(
    'default'        => '',
    'type'           => 'option',
    'capability'     => 'edit_theme_options',
	) );
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'main_background', array(
		'label' => 'Main Background',
		'section' => 'fin_backgrounds',
		'settings' => 'fin_theme_options[main_background]'
	) ) );
	
	// Nav Background
	$wp_customize->add_setting( 'fin_theme_options[nav_background]', array(
	  'default'        => '',
	  'type'           => 'option',
	  'capability'     => 'edit_theme_options',
	) );
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'nav_background', array(
		'label' => 'Navigation Background',
		'section' => 'fin_backgrounds',
		'settings' => 'fin_theme_options[nav_background]'
	) ) );
	
	// Content Background
	$wp_customize->add_setting( 'fin_theme_options[content_background]', array(
	  'default'        => '',
	  'type'           => 'option',
	  'capability'     => 'edit_theme_options',
	) );
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'content_background', array(
		'label' => 'Content Background',
		'section' => 'fin_backgrounds',
		'settings' => 'fin_theme_options[content_background]'
	) ) );
	
	/**** Colors ****/
	$wp_customize->add_section('fin_colors', array(
		'title'          => __( 'Colors', 'fin' ),
		'priority'       => 101,
	) );
	
	// Headings
	$wp_customize->add_setting( 'fin_theme_options[heading_color]', array(
	  'default'        => '',
	  'type'           => 'option',
	  'capability'     => 'edit_theme_options',
	) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'heading_color', array(
		'label'   => 'Headings Color',
		'section' => 'fin_colors',
		'settings'   => 'fin_theme_options[heading_color]',
	) ) );
	
	// Text
	$wp_customize->add_setting( 'fin_theme_options[text_color]', array(
	  'default'        => '',
	  'type'           => 'option',
	  'capability'     => 'edit_theme_options',
	) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'text_color', array(
		'label'   => 'Text Color',
		'section' => 'fin_colors',
		'settings'   => 'fin_theme_options[text_color]',
	) ) );
	
	// Link
	$wp_customize->add_setting( 'fin_theme_options[link_color]', array(
	  'default'        => '',
	  'type'           => 'option',
	  'capability'     => 'edit_theme_options',
	) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'link_color', array(
		'label'   => 'Link Color',
		'section' => 'fin_colors',
		'settings'   => 'fin_theme_options[link_color]',
	) ) );
	
	// Nav Links
	$wp_customize->add_setting( 'fin_theme_options[nav_link_color]', array(
	  'default'        => '',
	  'type'           => 'option',
	  'capability'     => 'edit_theme_options',
	) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'nav_link_color', array(
		'label'   => 'Nav Link Color',
		'section' => 'fin_colors',
		'settings'   => 'fin_theme_options[nav_link_color]',
	) ) );
	
	/**** Typography ****/
	$wp_customize->add_section('fin_typography', array(
		'title'          => __( 'Typography', 'fin' ),
		'priority'       => 102,
	) );
	
	// Headings Font
	$wp_customize->add_setting( 'fin_theme_options[heading_font]', array(
	  'default'        => '',
	  'type'           => 'option',
	  'capability'     => 'edit_theme_options',
	) );
	$wp_customize->add_control( 'fin_theme_options[heading_font]', array(
		'label'   => 'Headings Font',
		'section' => 'fin_typography',
		'type'    => 'select',
		'choices'    => array(
			'Great Vibes' => 'Great Vibes',
			'Croissant One' => 'Croissant One',
			'Elsie' => 'Elsie',
			'Sail' => 'Sail',
			'Sevillana' => 'Sevillana',
			'Cinzel Decorative' => 'Cinzel Decorative',
			'Oleo Script Swash Caps' => 'Oleo Script Swash Caps',
			'Alfa Slab One' => 'Alfa Slab One',
			'Special Elite' => 'Special Elite',
			'Glass Antiqua' => 'Glass Antiqua',
			'Poiret One' => 'Poiret One',
			'Cherry Swash' => 'Cherry Swash'
		),
	) );
	
	// Text Font
	$wp_customize->add_setting( 'fin_theme_options[text_font]', array(
	  'default'        => '',
	  'type'           => 'option',
	  'capability'     => 'edit_theme_options',
	) );
	$wp_customize->add_control( 'fin_theme_options[text_font]', array(
		'label'   => 'Main Text Font',
		'section' => 'fin_typography',
		'type'    => 'select',
		'choices'    => array(
			'Nixie One' => 'Nixie One',
			'Corben' => 'Corben',
			'Flamenco' => 'Flamenco',
			'Special Elite' => 'Special Elite',
			'Alegreya' => 'Alegreya',
			'Prata' => 'Prata',
			'Cutive' => 'Cutive',
			'Kotta One' => 'Kotta One',
			'Merriweather' => 'Merriweather',
			'Poiret One' => 'Poiret One',
			'Raleway' => 'Raleway',
			'Duru Sans' => 'Duru Sans'
		),
	) );
	
	/**** Settings ****/
	$wp_customize->add_section('fin_settings', array(
		'title'          => __( 'Settings', 'fin' ),
		'priority'       => 103,
	) );
	
	// Analytics
	$wp_customize->add_setting( 'fin_theme_options[analytics]', array(
		'default'       => 'UA-#######-#',
		'type'					=> 'option'
	) );
	
	$wp_customize->add_control( 'fin_theme_options[analytics]', array(
		'label'   => 'Google Analytics Code',
		'section' => 'fin_settings',
		'type'    => 'text',
	) );
	
	// Keywords
	$wp_customize->add_setting( 'fin_theme_options[keywords]', array(
		'default'       => '',
		'type'					=> 'option'
	) );
	
	$wp_customize->add_control( 'fin_theme_options[keywords]', array(
		'label'   => 'Default Keywords',
		'section' => 'fin_settings',
		'type'    => 'text',
	) );
	
	// Include Search in Nav
	$wp_customize->add_setting( 'fin_theme_options[include_search]', array(
		'default'       => '',
		'type'					=> 'option'
	) );
	$wp_customize->add_control( 'include_search', array(
		'settings' => 'fin_theme_options[include_search]',
		'label'    => __( 'Include Searchbar in Nav' ),
		'section'  => 'fin_settings',
		'type'     => 'checkbox'
	) );
	
	// Suppress Comments Closed Warning
	$wp_customize->add_setting( 'fin_theme_options[comments_warning]', array(
		'default'       => '',
		'type'					=> 'option'
	) );
	$wp_customize->add_control( 'comments_warning', array(
		'settings' => 'fin_theme_options[comments_warning]',
		'label'    => __( 'Show warning for Comments Closed' ),
		'section'  => 'fin_settings',
		'type'     => 'checkbox'
	) );
	
	/**** Social ****/
	$wp_customize->add_section('fin_social', array(
		'title'          => __( 'Social Links', 'fin' ),
		'priority'       => 104,
	) );
	
	// include ?
	$wp_customize->add_setting( 'fin_theme_options[social]', array(
		'default'       => '',
		'type'					=> 'option'
	) );
	$wp_customize->add_control( 'social', array(
		'settings' => 'fin_theme_options[social]',
		'label'    => __( 'Include Social Icons in the footer' ),
		'section'  => 'fin_social',
		'type'     => 'checkbox',
		'priority' => 1
	) );
	
	// Facebook
	$wp_customize->add_setting( 'fin_theme_options[social_facebook]', array(
		'default'       => '',
		'type'					=> 'option'
	) );
	
	$wp_customize->add_control( 'fin_theme_options[social_facebook]', array(
		'label'   => 'Facebook URL',
		'section' => 'fin_social',
		'type'    => 'text',
	) );
	
	// Twitter
	$wp_customize->add_setting( 'fin_theme_options[social_twitter]', array(
		'default'       => '',
		'type'					=> 'option'
	) );
	
	$wp_customize->add_control( 'fin_theme_options[social_twitter]', array(
		'label'   => 'Twitter URL',
		'section' => 'fin_social',
		'type'    => 'text',
	) );
	
	// Google Plus
	$wp_customize->add_setting( 'fin_theme_options[social_google_plus]', array(
		'default'       => '',
		'type'					=> 'option'
	) );
	
	$wp_customize->add_control( 'fin_theme_options[social_google_plus]', array(
		'label'   => 'Google+ URL',
		'section' => 'fin_social',
		'type'    => 'text',
	) );
	
	// Pinterest
	$wp_customize->add_setting( 'fin_theme_options[social_pinterest]', array(
		'default'       => '',
		'type'					=> 'option'
	) );
	
	$wp_customize->add_control( 'fin_theme_options[social_pinterest]', array(
		'label'   => 'Pinterest URL',
		'section' => 'fin_social',
		'type'    => 'text',
	) );
	
	// Linkedin
	$wp_customize->add_setting( 'fin_theme_options[social_linkedin]', array(
		'default'       => '',
		'type'					=> 'option'
	) );
	
	$wp_customize->add_control( 'fin_theme_options[social_linkedin]', array(
		'label'   => 'Linkedin URL',
		'section' => 'fin_social',
		'type'    => 'text',
	) );
	
	// Github
	$wp_customize->add_setting( 'fin_theme_options[social_github]', array(
		'default'       => '',
		'type'					=> 'option'
	) );
	
	$wp_customize->add_control( 'fin_theme_options[social_github]', array(
		'label'   => 'Github URL',
		'section' => 'fin_social',
		'type'    => 'text',
	) );
	
	/**** Maintenance ****/
	$wp_customize->add_section('fin_construction', array(
		'title'          => __( 'Construction Page', 'fin' ),
		'priority'       => 304,
	) );
	
	// include ?
	$wp_customize->add_setting( 'fin_theme_options[construction]', array(
		'default'       => '',
		'type'					=> 'option'
	) );
	$wp_customize->add_control( 'construction', array(
		'settings' => 'fin_theme_options[construction]',
		'label'    => __( 'Redirect to "Under Construction"' ),
		'section'  => 'fin_construction',
		'type'     => 'checkbox',
		'priority' => 1
	) );
}
add_action('customize_register', 'fin_customize_register');

/**
 * Add relevant styles to wp_head
 *
 */
function fin_add_custom_styles() {
	$options = get_option('fin_theme_options');
	$output = '';
	$main_background = $options['main_background'];
	if($main_background) {
		$output .= "html, body { background:url(" . $main_background . "); }";
	}
	
	$nav_background = $options['nav_background'];
	if($nav_background) {
		$output .= "#header { background:url(" . $nav_background . "); }";
	}
	
	$content_background = $options['content_background'];
	if($content_background) {
		$output .= "#content { background:url(" . $content_background . "); }";
	}
	
	$heading_color = $options['heading_color'];
	if($heading_color) {
		$output .= "h1,h2,h3,h4,h5,h6 { color: $heading_color; }";
	}
	
	$text_color = $options['text_color'];
	if($text_color) {
		$output .= "body,div,dl,dt,dd,ul,ol,li,pre,form,p,blockquote,th,td { color: $text_color; }";
	}
	
	$link_color = $options['link_color'];
	if($link_color) {
		$output .= "a { color: $link_color;}
		a:visited {color: " . brightness($link_color, -.95) . "; }
		a:hover { color: " . brightness($link_color, .85) . "; }";
	}
	
	$nav_link_color = $options['nav_link_color'];
	if($nav_link_color) {
		$output .= "#nav-main a, a:visited { color: $nav_link_color; }
		#nav-main a:hover { color: " . brightness($link_color, .85) . "; }";
	}
	
	$heading_font = $options['heading_font'];
	if($heading_font) {
		$output .= "h1,h2,h3,h4,h5,h6 { font-family: $heading_font; }";
	}
	
	$text_font = $options['text_font'];
	if($text_font) {
		$output .= "body,div,dl,dt,dd,ul,ol,li,pre,form,p,blockquote,th,td { font-family: $text_font; }";
	}
	
	if($output != ''){ 
		echo '<style>' . $output . '</style>';
	}
}
add_action('wp_head', 'fin_add_custom_styles');