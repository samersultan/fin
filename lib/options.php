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
	
	// Nav
	$wp_customize->add_setting( 'fin_theme_options[nav_color]', array(
	  'default'        => '',
	  'type'           => 'option',
	  'capability'     => 'edit_theme_options',
	) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'nav_color', array(
		'label'   => 'Nav Color',
		'section' => 'fin_colors',
		'settings'   => 'fin_theme_options[nav_color]',
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
			'Lobster Two' => 'Lobster Two',
			'Quattrocento' => 'Quattrocento',
			'Droid Sans' => 'Droid Sans',
			'PT Sans' => 'PT Sans',
			'Yanone Kaffeesatz' => 'Yanone Kaffeesatz',
			'Cabin' => 'Cabin',
			'Black Ops One' => 'Black Ops One',
			'Nixie One' => 'Nixie One',
			'Bangers' => 'Bangers',
			'Monofett' => 'Monofett',
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
			'Lobster Two' => 'Lobster Two',
			'Quattrocento' => 'Quattrocento',
			'Droid Sans' => 'Droid Sans',
			'PT Sans' => 'PT Sans',
			'Yanone Kaffeesatz' => 'Yanone Kaffeesatz',
			'Cabin' => 'Cabin',
			'Black Ops One' => 'Black Ops One',
			'Nixie One' => 'Nixie One',
			'Bangers' => 'Bangers',
			'Monofett' => 'Monofett',
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
		if($main_background['image']) {
			$output .= "body { background:url(" . $main_background['image'] . "); }";
		}elseif($main_background['image']) {
			$output .= "body { background-color:" . $main_background['color'] . ";}";
		}	
	}
	
	$content_background = $options['content_background'];
	if($content_background) {
		if($content_background['image']) {
			$output .= "#content { background:url(" . $content_background['image'] . "); }";
		}elseif($content_background['image']) {
			$output .= "#content { background-color:" . $content_background['color'] . ";}";
		}	
	}
	
	$heading_color = $options['heading_color'];
	if($heading_color) {
		$output .= "h1,h2,h3,h4,h5,h6 { color: $heading_color; }";
	}
	
	$main_color = $options['text_color'];
	if($main_color) {
		$output .= "body,div,dl,dt,dd,ul,ol,li,pre,form,p,blockquote,th,td { color: $main_color; }";
	}
	
	$link_color = $options['link_color'];
	if($link_color) {
		$output .= "a { color: $link_color;}
		a:hover {color: " . brightness($link_color, .25) . "; }
		a:visited { color: " . brightness($link_color, -.25) . "; }";
	}
	
	$nav_color = $options['nav_color'];
	if($nav_color) {
		$output .= "#nav-main { background: $nav_color; }";
	}
	
	$nav_link_color = $options['nav_link_color'];
	if($nav_link_color) {
		$output .= "#nav-main a { color: $nav_link_color; }";
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