<?php
/**
 * Bytemonkey Theme Customizer
 *
 * @package bytemonkey
 */

/**
 * Setup the WordPress core custom header feature.
 *
 * @uses bytemonkey_header_style()
 *
 */
function bytemonkey_custom_header_and_background() {
	$color_scheme             = bytemonkey_get_color_scheme();
	$default_background_color = trim( $color_scheme[3], '#' );
	$default_text_color       = trim( $color_scheme[2], '#' );
	
	add_theme_support( 'custom-header', apply_filters( 'bytemonkey_custom_header_args', array(
		'default-image'          => '',
		'default-text-color'     => 'd6d6d6',
		'width'                  => 300,
		'height'                 => 76,
		'flex-height'			 => true,
		'flex-width'			 => true,
		'wp-head-callback'       => 'bytemonkey_header_style',
	)));
	
	// Setup the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'bytemonkey_custom_background_args', array(
		'default-color' => 'f2f2f2',
		'default-image' => '',
	)));
}
add_action( 'after_setup_theme', 'bytemonkey_custom_header_and_background' );

if ( ! function_exists( 'bytemonkey_header_style' ) ) :
/**
 * Styles the header image and text displayed on the blog
 *
 * @see bytemonkey_custom_header_setup().
 */
function bytemonkey_header_style() {
	$header_text_color = get_header_textcolor();

	// If no custom options for text are set, let's bail
	// get_header_textcolor() options: HEADER_TEXTCOLOR is default, hide text (returns 'blank') or any hex value
	if ( HEADER_TEXTCOLOR == $header_text_color ) {
		return;
	}

	// If we get this far, we have custom styles. Let's do this.
	?>
	<style type="text/css">
	<?php
		// Has the text been hidden?
		if ( 'blank' == $header_text_color ) :
	?>
		.site-title,
		.site-description {
			position: absolute;
			clip: rect(1px, 1px, 1px, 1px);
		}
	<?php
		// If the user has set a custom color for the text use that
		else :
	?>
		.navbar > .container .navbar-brand {
			color: #<?php echo $header_text_color; ?>;
		}
	<?php endif; ?>
	</style>
	<?php
}
endif; // bytemonkey_header_style

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function bytemonkey_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';
}
add_action( 'customize_register', 'bytemonkey_customize_register' );

/**
 * Options for bytemonkey Theme Customizer.
 */
function bytemonkey_customizer( $wp_customize ) {
	// Selected Color Scheme
	$color_scheme = bytemonkey_get_color_scheme();
	
	// Layout options
	$layout_options = bytemonkey_get_layout_options();
	
	// Typography Defaults
	$typography_defaults = bytemonkey_get_typography_defaults();
	
	// Typography Options
	$typography_options = bytemonkey_get_typography_options();
	
	$wp_customize->add_setting( 'bytemonkey_color_scheme', array(
		'default'           => 'default',
		'sanitize_callback' => 'bytemonkey_sanitize_color_scheme',
		'transport'         => 'postMessage',
	));

	$wp_customize->add_control( 'bytemonkey_color_scheme', array(
		'label'    => __( 'Base Color Scheme', 'bytemonkey' ),
		'section'  => 'colors',
		'type'     => 'select',
		'choices'  => bytemonkey_get_color_scheme_choices(),
		'priority' => 1,
	));

	$wp_customize->add_setting( 'bytemonkey_primary_element_color', array(
		'default'           => $color_scheme[0],
		'sanitize_callback' => 'bytemonkey_sanitize_hexcolor',
		'transport'         => 'postMessage',
	));

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bytemonkey_primary_element_color', array(
		'label'       => __( 'Primary Element Color', 'bytemonkey' ),
		'section'     => 'colors',
	)));

	// Remove the core header textcolor control, as it shares the main text color.
	$wp_customize->remove_control( 'header_textcolor' );

	$wp_customize->add_setting( 'bytemonkey_secondary_element_color', array(
		'default'           => $color_scheme[1],
		'sanitize_callback' => 'bytemonkey_sanitize_hexcolor',
		'transport'         => 'postMessage',
	));

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bytemonkey_secondary_element_color', array(
		'label'       => __( 'Secondary Element Color', 'bytemonkey' ),
		'section'     => 'colors',
	)));
	
	$wp_customize->add_setting( 'bytemonkey_background_color', array(
		'default'           => $color_scheme[2],
		'sanitize_callback' => 'bytemonkey_sanitize_hexcolor',
		'transport'         => 'postMessage',
	));

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bytemonkey_background_color', array(
		'label'       => __( 'Background Color', 'bytemonkey' ),
		'section'     => 'colors',
	)));


	$wp_customize->add_setting( 'bytemonkey_main_text_color', array(
		'default'           => $color_scheme[3],
		'sanitize_callback' => 'bytemonkey_sanitize_hexcolor',
		'transport'         => 'postMessage',
	));

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bytemonkey_main_text_color', array(
		'label'       => __( 'Primary Text Color', 'bytemonkey' ),
		'section'     => 'colors',
	)));

	$wp_customize->add_setting( 'bytemonkey_header_text_color', array(
		'default'           => $color_scheme[4],
		'sanitize_callback' => 'bytemonkey_sanitize_hexcolor',
		'transport'         => 'postMessage',
	));

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bytemonkey_header_text_color', array(
		'label'       => __( 'Header/Footer Text Color', 'bytemonkey' ),
		'section'     => 'colors',
	)));
	
	$wp_customize->add_setting( 'bytemonkey_header_link_active_color', array(
		'default'           => $color_scheme[5],
		'sanitize_callback' => 'bytemonkey_sanitize_hexcolor',
		'transport'         => 'postMessage',
	));

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bytemonkey_header_link_active_color', array(
		'label'       => __( 'Header/Footer Link Coloration', 'bytemonkey' ),
		'section'     => 'colors',
	)));
	
	$wp_customize->add_setting( 'bytemonkey_footer_widget_background_color', array(
		'default'           => $color_scheme[6],
		'sanitize_callback' => 'bytemonkey_sanitize_hexcolor',
		'transport'         => 'postMessage',
	));

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bytemonkey_footer_widget_background_color', array(
		'label'       => __( 'Footer Widget Area Background Color', 'bytemonkey' ),
		'section'     => 'colors',
	)));

	$wp_customize->add_section( 'bytemonkey_content_section' , array(
		'title'      => esc_html__( 'Content Options', 'bytemonkey' ),
		'priority'   => 20,
	));
	
		$wp_customize->add_setting('bytemonkey_excerpt_length', array(
			'default' 			=> '150',
			'sanitize_callback' => 'bytemonkey_sanitize_number'
		));
		$wp_customize->add_control('bytemonkey_excerpt_length', array(
			'label' 		=> __('Post Excerpts', 'bytemonkey'),
			'description' 	=> sprintf(__('Length of post exceprts', 'bytemonkey')),
			'section' 		=> 'bytemonkey_content_section',
			'priority'		=> 100,
			'type' 			=> 'number',
			'input_attrs'	=> array(
			  'min'		=> 50,
			  'max'		=> 800,
			  'step'	=> 25
			)
		));
		
		$wp_customize->add_setting( 'bytemonkey_excerpt_show', array(
			'default'           => 1,
			'sanitize_callback' => 'bytemonkey_sanitize_checkbox',
		));
		$wp_customize->add_control( 'bytemonkey_excerpt_show', array(
			'label'     => esc_html__( 'Show post excerpts?', 'bytemonkey' ),
			'section'   => 'bytemonkey_content_section',
			'priority'  => 110,
			'type'      => 'checkbox'
		));

		$wp_customize->add_setting( 'bytemonkey_page_comments', array(
				'default' 			=> 0,
				'sanitize_callback' => 'bytemonkey_sanitize_checkbox',
		));
		$wp_customize->add_control( 'bytemonkey_page_comments', array(
			'label'		=> esc_html__( 'Display Comments on Static Pages?', 'bytemonkey' ),
			'section'	=> 'bytemonkey_content_section',
			'priority'	=> 120,
			'type'      => 'checkbox',
		));
		
		$wp_customize->add_setting( 'bytemonkey_author_block', array(
				'default' 			=> 1,
				'sanitize_callback' => 'bytemonkey_sanitize_checkbox',
		));
		$wp_customize->add_control( 'bytemonkey_author_block', array(
				'label'			=> esc_html__( 'Display Author Block?', 'bytemonkey' ),
				'description'	=> sprintf(__('If there are multiple authors, show author bio at the end of the posts.', 'bytemonkey')),
				'section'		=> 'bytemonkey_content_section',
				'priority'		=> 130,
				'type'      	=> 'checkbox',
		));
		
		$wp_customize->add_setting('bytemonkey_footer_custom_text', array(
			'default' 			=> '',
			'sanitize_callback' => 'bytemonkey_sanitize_strip_slashes'
		));
		$wp_customize->add_control('bytemonkey_footer_custom_text', array(
			'label' 			=> __('Footer information', 'bytemonkey'),
			'description' 		=> sprintf(__('Copyright text in footer', 'bytemonkey')),
			'section' 			=> 'bytemonkey_content_section',
			'priority'			=> 150,
			'type' 				=> 'textarea'
		));
		
		$wp_customize->add_setting('bytemonkey_footer_social_icons', array(
			'default' 			=> 0,
			'type' 				=> 'option',
			'sanitize_callback' => 'bytemonkey_sanitize_checkbox'
		));
		$wp_customize->add_control('bytemonkey_footer_social_icons', array(
			'label' 			=> __('Footer Social Icons', 'bytemonkey'),
			'description' 		=> sprintf(__('Check to show social icons in footer', 'bytemonkey')),
			'section' 			=> 'bytemonkey_content_section',
			'type' 				=> 'checkbox',
			'priority'			=> 160
		));
		
	$wp_customize->add_section('bytemonkey_layout_options', array(
		'title' 		=> __('Layout options', 'bytemonkey'),
		'priority' 		=> 30,
	));	
		$wp_customize->add_setting('bytemonkey_sticky_header', array(
			'default' 			=> 0,
			'sanitize_callback' => 'bytemonkey_sanitize_checkbox'
		));
		$wp_customize->add_control('bytemonkey_sticky_header', array(
			'label' 			=> __('Sticky Header', 'bytemonkey'),
			'description' 		=> sprintf(__('Check to show fixed header', 'bytemonkey')),
			'section' 			=> 'bytemonkey_layout_options',
			'type' 				=> 'checkbox',
		));
		
		$wp_customize->add_setting('bytemonkey_frontpage_layout', array(
			 'default' 				=> 'side-pull-left',
			 'sanitize_callback' 	=> 'bytemonkey_sanitize_layout'
		));
		$wp_customize->add_control('bytemonkey_frontpage_layout', array(
			 'label' 				=> __('Frontpage Layout', 'bytemonkey'),
			 'section' 				=> 'bytemonkey_layout_options',
			 'type'    				=> 'select',
			 'description' 			=> __('Default Front Page Layout', 'bytemonkey'),
			 'choices'    			=> $layout_options
		));
		
		$wp_customize->add_setting('bytemonkey_post_layout', array(
			 'default' 				=> 'full-width',
			 'sanitize_callback' 	=> 'bytemonkey_sanitize_layout'
		));
		$wp_customize->add_control('bytemonkey_post_layout', array(
			 'label' 				=> __('Post Layout', 'bytemonkey'),
			 'section' 				=> 'bytemonkey_layout_options',
			 'type'                 => 'select',
			 'description' 			=> __('Default Blog Post Layout', 'bytemonkey'),
			 'choices'    			=> $layout_options
		));
		
		$wp_customize->add_setting('bytemonkey_page_layout', array(
			 'default' 				=> 'side-pull-left',
			 'sanitize_callback' 	=> 'bytemonkey_sanitize_layout'
		));
		$wp_customize->add_control('bytemonkey_page_layout', array(
			 'label' 				=> __('Page Layout', 'bytemonkey'),
			 'section' 				=> 'bytemonkey_layout_options',
			 'type'    				=> 'select',
			 'description' 			=> __('Default Static Page Layout', 'bytemonkey'),
			 'choices'    			=> $layout_options
		));
		
		$wp_customize->add_setting('bytemonkey_site_layout', array(
			 'default' 				=> 'side-pull-left',
			 'sanitize_callback' 	=> 'bytemonkey_sanitize_layout'
		));
		$wp_customize->add_control('bytemonkey_site_layout', array(
			 'label' 				=> __('Misc. Layout Options', 'bytemonkey'),
			 'section' 				=> 'bytemonkey_layout_options',
			 'type'    				=> 'select',
			 'description' 			=> __('Default layout for all other areas', 'bytemonkey'),
			 'choices'    			=> $layout_options
		));
	
	/* bytemonkey SEO Options */
	$wp_customize->add_section( 'bytemonkey_seo_section' , array(
		'title'      => esc_html__( 'SEO Options', 'bytemonkey' ),
		'priority'   => 100,
	));
		
		$wp_customize->add_setting( 'bytemonkey_google_cse_search', array(
			'default'           => 1,
			'sanitize_callback' => 'bytemonkey_sanitize_checkbox',
		));
		$wp_customize->add_control( 'bytemonkey_google_cse_search', array(
			'label'     => esc_html__( 'Use Google CSE For Search?', 'bytemonkey' ),
			'section'   => 'bytemonkey_seo_section',
			'priority'  => 110,
			'type'      => 'checkbox'
		));
		
		$wp_customize->add_setting('bytemonkey_google_cse_id', array(
			'default' 			=> '004364479666620937412:d9zjyedvax4',
			'sanitize_callback' => 'esc_url_raw'
		));
		$wp_customize->add_control('bytemonkey_google_cse_id', array(
			'label' 			=> __('Google CSE ID', 'bytemonkey'),
			'section' 			=> 'bytemonkey_seo_section',
			'description' 		=> __('Enter your Google CSE ID code', 'bytemonkey'),
			'type' 				=> 'text'
		));

		$wp_customize->add_setting( 'bytemonkey_yoast_breadcrumbs', array(
				'default' 			=> 0,
				'sanitize_callback' => 'bytemonkey_sanitize_checkbox',
		));
		$wp_customize->add_control( 'bytemonkey_yoast_breadcrumbs', array(
			'label'			=> esc_html__( 'Enable Yoast Breadcrumbs?', 'bytemonkey' ),
			'section'		=> 'bytemonkey_seo_section',
			'description' 	=> __('Displays Yoast Breadcrumbs in theme if the plugin is installed and has them enabled.', 'bytemonkey'),
			'priority'		=> 120,
			'type'      	=> 'checkbox',
		));

	/* bytemonkey Typography Options */
	$wp_customize->add_section('bytemonkey_typography_options', array(
		'title' 					=> __('Typography', 'bytemonkey'),
		'priority' 					=> 40
	));		
		$wp_customize->add_setting('bytemonkey_main_typography_face', array(
			'default' 			=> $typography_defaults['main_face'],
			'sanitize_callback' => 'bytemonkey_sanitize_typo_face',
			'transport'         => 'postMessage',
		));
		$wp_customize->add_control('bytemonkey_main_typography_face', array(
			'label' 			=> __('Primary Typography', 'bytemonkey'),
			'description' 		=> __('Used page/post body, widget text, etc.', 'bytemonkey'),
			'section' 			=> 'bytemonkey_typography_options',
			'type'    			=> 'select',
			'choices'    		=> $typography_options['faces']
		));
		$wp_customize->add_setting('bytemonkey_main_typography_size', array(
			'default' 			=> $typography_defaults['main_size'],
			'sanitize_callback' => 'bytemonkey_sanitize_typo_size',
			'transport'         => 'postMessage',
		));
		$wp_customize->add_control('bytemonkey_main_typography_size', array(
			'section' 			=> 'bytemonkey_typography_options',
			'type'    			=> 'select',
			'choices'    		=> $typography_options['sizes']
		));
		$wp_customize->add_setting('bytemonkey_main_typography_style', array(
			'default' 			=> $typography_defaults['main_style'],
			'sanitize_callback' => 'bytemonkey_sanitize_typo_style',
			'transport'         => 'postMessage',
		));
		$wp_customize->add_control('bytemonkey_main_typography_style', array(
			'section' 			=> 'bytemonkey_typography_options',
			'type'    			=> 'select',
			'choices'    		=> $typography_options['styles']
		));
		
		$wp_customize->add_setting('bytemonkey_heading_typography_face', array(
			'default' 			=> $typography_defaults['head_face'],
			'sanitize_callback' => 'bytemonkey_sanitize_typo_face',
			'transport'         => 'postMessage',
		));
		$wp_customize->add_control('bytemonkey_heading_typography_face', array(
			'label' 			=> __('Heading Typography', 'bytemonkey'),
			'description' 		=> __('Page/Post Titles', 'bytemonkey'),
			'section' 			=> 'bytemonkey_typography_options',
			'type'    			=> 'select',
			'choices'    		=> $typography_options['faces']
		));
		$wp_customize->add_setting('bytemonkey_heading_typography_style', array(
			'default' 			=> $typography_defaults['head_style'],
			'sanitize_callback' => 'bytemonkey_sanitize_typo_style',
			'transport'         => 'postMessage',
		));
		$wp_customize->add_control('bytemonkey_heading_typography_style', array(
			'section' 			=> 'bytemonkey_typography_options',
			'type'    			=> 'select',
			'choices'    		=> $typography_options['styles']
		));
		
		$wp_customize->add_setting('bytemonkey_widget_typography_face', array(
			'default' 			=> $typography_defaults['widg_face'],
			'sanitize_callback' => 'bytemonkey_sanitize_typo_face',
			'transport'         => 'postMessage',
		));
		$wp_customize->add_control('bytemonkey_widget_typography_face', array(
			'label' 			=> __('Widget Heading Typography', 'bytemonkey'),
			'description' 		=> __('Widget title typography', 'bytemonkey'),
			'section' 			=> 'bytemonkey_typography_options',
			'type'    			=> 'select',
			'choices'    		=> $typography_options['faces']
		));
		$wp_customize->add_setting('bytemonkey_widget_typography_size', array(
			'default' 			=> $typography_defaults['widg_size'],
			'sanitize_callback' => 'bytemonkey_sanitize_typo_size',
			'transport'         => 'postMessage',
		));
		$wp_customize->add_control('bytemonkey_widget_typography_size', array(
			'section' 			=> 'bytemonkey_typography_options',
			'type'    			=> 'select',
			'choices'    		=> $typography_options['sizes']
		));
		$wp_customize->add_setting('bytemonkey_widget_typography_style', array(
			'default' 			=> $typography_defaults['widg_style'],
			'sanitize_callback' => 'bytemonkey_sanitize_typo_style',
			'transport'         => 'postMessage',
		));
		$wp_customize->add_control('bytemonkey_widget_typography_style', array(
			'section' 			=> 'bytemonkey_typography_options',
			'type'    			=> 'select',
			'choices'    		=> $typography_options['styles']
		));

	 /* bytemonkey Action Options */
	$wp_customize->add_section('bytemonkey_action_options', array(
		'title' 				=> __('Call For Action', 'bytemonkey'),
		'priority' 				=> 100
	));
		$wp_customize->add_setting('bytemonkey_w2f_cfa_text', array(
			'default' 			=> '',
			'sanitize_callback' => 'bytemonkey_sanitize_strip_slashes'
		));
		$wp_customize->add_control('bytemonkey_w2f_cfa_text', array(
			'label' 			=> __('Call For Action Text', 'bytemonkey'),
			'description' 		=> sprintf(__('Enter the text for call for action section', 'bytemonkey')),
			'section' 			=> 'bytemonkey_action_options',
			'type' 				=> 'textarea'
		));

		$wp_customize->add_setting('bytemonkey_w2f_cfa_button', array(
			'default' 			=> '',
			'type' 				=> 'option',
			'sanitize_callback' => 'bytemonkey_sanitize_nohtml'
		));
		$wp_customize->add_control('bytemonkey_w2f_cfa_button', array(
			'label' 			=> __('Call For Action Button Title', 'bytemonkey'),
			'section' 			=> 'bytemonkey_action_options',
			'description' 		=> __('Enter the title for Call For Action button', 'bytemonkey'),
			'type' 				=> 'text'
		));

		$wp_customize->add_setting('bytemonkey_w2f_cfa_link', array(
			'default' 			=> '',
			'sanitize_callback' => 'esc_url_raw'
		));
		$wp_customize->add_control('bytemonkey_w2f_cfa_link', array(
			'label' 			=> __('CFA button link', 'bytemonkey'),
			'section' 			=> 'bytemonkey_action_options',
			'description' 		=> __('Enter the link for Call For Action button', 'bytemonkey'),
			'type' 				=> 'text'
		));

}
add_action( 'customize_register', 'bytemonkey_customizer' );

/**
 * Retrieves the current Bytemonkey color scheme.
 *
 * @return array An associative array of either the current or default color scheme HEX values.
 */
function bytemonkey_get_color_scheme() {
	$color_scheme_option = get_theme_mod( 'bytemonkey_color_scheme', 'default' );
	$color_schemes       = bytemonkey_get_color_schemes();

	if ( array_key_exists( $color_scheme_option, $color_schemes ) ) {
		return $color_schemes[ $color_scheme_option ]['colors'];
	}

	return $color_schemes['default']['colors'];
}

/**
 * Retrieves an array of color scheme choices registered for Bytemonkey
 *
 *
 * @return array Array of color schemes.
 */
function bytemonkey_get_color_scheme_choices() {
	$color_schemes                = bytemonkey_get_color_schemes();
	$color_scheme_control_options = array();

	foreach ( $color_schemes as $color_scheme => $value ) {
		$color_scheme_control_options[ $color_scheme ] = $value['label'];
	}

	return $color_scheme_control_options;
}


/**
 * Binds the JS listener to make Customizer color_scheme control.
 *
 * Passes color scheme data as colorScheme global.
 *
 */
function bytemonkey_customize_control_js() {
	wp_enqueue_script( 'color-scheme-control', get_template_directory_uri() . '/js/color-scheme-control.js', array( 'customize-controls', 'iris', 'underscore', 'wp-util' ), '20150926', true );
	wp_localize_script( 'color-scheme-control', 'colorScheme', bytemonkey_get_color_schemes() );
}
add_action( 'customize_controls_enqueue_scripts', 'bytemonkey_customize_control_js' );

/**
 * Binds JS handlers to make the Customizer preview reload changes asynchronously.
 *
 */
function bytemonkey_customize_preview_js() {
	wp_enqueue_script( 'bytemonkey-customize-preview', get_template_directory_uri() . '/js/customize-preview.js', array( 'customize-preview' ), '20150922', true );
}
add_action( 'customize_preview_init', 'bytemonkey_customize_preview_js' );

/**
 * Enqueues front-end CSS for the primary color.
 *
 * @see wp_add_inline_style()
 */
function bytemonkey_primary_color_css() {
	$color_scheme    = bytemonkey_get_color_scheme();
	$default_color   = $color_scheme[0];
	$primary_color 	 = get_theme_mod( 'bytemonkey_primary_color', $default_color );

	// Don't do anything if the current color is the default.
	if ( $primary_color === $default_color ) {
		return;
	}

	$css = '
		/* Custom Primary Color */
		a,
		h1,
		h2,
		h3,
		h4,
		h5,
		h6,
		.h1,
		.h2,
		.h3,
		.h4,
		.h5,
		.h6,
		.entry_title,
		.entry_title a,
		.pagination li a,
		#secondary .widget a,
		#secondary .widget .post-content a,
		#infinite-handle span,
		.navbar-default .navbar-nav > .active > a,
		.navbar-default .navbar-nav > .active > a:hover,
		.navbar-default .navbar-nav > .active > a:focus,
		.navbar-default .navbar-nav > li > a:hover,
		.navbar-default .navbar-nav > li > a:focus,
		.navbar-default .navbar-nav > .open > a,
		.navbar-default .navbar-nav > .open > a:hover,
		.navbar-default .navbar-nav > .open > a:focus,
		.navbar-default .navbar-nav .current-menu-ancestor a.dropdown-toggle,
		#footer-area .footer-widget-area a,
		.pagination>li>a,
		.pagination>li>span,
		.pagination>li>a:focus,
		.pagination>li>a:hover,
		.pagination>li>span:focus,
		.pagination>li>span:hover,
		.cfa-text,
		.cfa-button,
		.cfa-button a {
			color: %1$s;
		}

		.page-links span,
		.page-links a:hover span,
		.pagination>.active>a,
		.pagination>.active>span,
		.pagination>.active>a:hover,
		.pagination>.active>span:hover,
		.pagination>.active>a:focus,
		.pagination>.active>span:focus,
		.tagcloud a:hover,
		.btn-default, .label-default,
		button, .button,
		#infinite-handle span:hover,
		.dropdown-menu > li > a:hover,
		.dropdown-menu > li > a:focus,
		.navbar-default .navbar-nav .open .dropdown-menu > li > a:hover,
		.navbar-default .navbar-nav .open .dropdown-menu > li > a:focus,
		.navbar-default .navbar-nav .open .dropdown-menu>.active>a,
		.navbar-default .navbar-nav .open .dropdown-menu>.active>a:focus,
		.navbar-default .navbar-nav .open .dropdown-menu>.active>a:hover,
		.dropdown-menu>.active>a,
		.dropdown-menu>.active>a:hover,
		.dropdown-menu>.active>a:focus,
		.navigation .wp-pagenavi-pagination span.current,
		.navigation .wp-pagenavi-pagination a:hover,
		#image-navigation .nav-previous a:hover,
		#image-navigation .nav-next a:hover {
			background-color: %1$s;
		}

		.pagination>.active>a,
		.pagination>.active>span,
		.pagination>.active>a:hover,
		.pagination>.active>span:hover,
		.pagination>.active>a:focus,
		.pagination>.active>span:focus,
		.tagcloud a:hover,
		.btn-default, .label-default,
		.navigation .wp-pagenavi-pagination span.current,
		.navigation .wp-pagenavi-pagination a:hover,
		.cfa-button {
			border-color: %1$s;
		}
		
		@media (max-width: 767px) {
			.navbar-default .navbar-nav .open .dropdown-menu>.active>a,
			.navbar-default .navbar-nav .open .dropdown-menu>.active>a:focus,
			.navbar-default .navbar-nav .open .dropdown-menu>.active>a:hover {
				background-color: %1$s;
			}
	';

	wp_add_inline_style( 'bytemonkey-style', sprintf( $css, $primary_color ) );
}
add_action( 'wp_enqueue_scripts', 'bytemonkey_primary_color_css', 11 );

/**
	* Enqueues front-end CSS for the secondary color.
 *
 * @see wp_add_inline_style()
 */
function bytemonkey_secondary_color_css() {
	$color_scheme          = bytemonkey_get_color_scheme();
	$default_color         = $color_scheme[1];
	$secondary_color 	   = get_theme_mod( 'bytemonkey_secondary_color', $default_color );

	// Don't do anything if the current color is the default.
	if ( $secondary_color === $default_color ) {
		return;
	}

	$css = '
		/* Custom Secondary Color */
		a:hover,
		a:focus,
		.entry-title a:hover,
		.pagination li:hover a,
		.widgettitle,
		#footer-area .footer-widget-area a:hover,
		#footer-area .footer-widget-area a:focus,
		.cfa-button:hover {
			color: %1$s;
		}
		
		.btn-default:hover,
		.label-default[href]:hover,
		.label-default[href]:focus,
		.btn-default:hover, .btn-default:focus,
		.btn-default:active,
		.btn-default.active,
		#image-navigation .nav-previous a:hover,
		#image-navigation .nav-next a:hover,
		button:hover, .button:hover,
		.navbar.navbar-default,
		.cfa-button:hover,
		#colophon {
			background-color: %1$s;
		}
		
		btn-default:hover,
		.label-default[href]:hover,
		.label-default[href]:focus,
		.btn-default:hover, .btn-default:focus,
		.btn-default:active,
		.btn-default.active,
		#image-navigation .nav-previous a:hover,
		#image-navigation .nav-next a:hover	{
			border-color: %1$s;
		}

	';

	wp_add_inline_style( 'bytemonkey-style', sprintf( $css, $secondary_color ) );
}
add_action( 'wp_enqueue_scripts', 'bytemonkey_secondary_color_css', 11 );

/**
 * Enqueues front-end CSS for the page background color.
 *
 * @see wp_add_inline_style()
 */
function bytemonkey_background_color_css() {
	$color_scheme          = bytemonkey_get_color_scheme();
	$default_color         = $color_scheme[2];
	$background_color 	   = get_theme_mod( 'bytemonkey_background_color', $default_color );

	// Don't do anything if the current color is the default.
	if ( $background_color === $default_color ) {
		return;
	}

	$css = '
		/* Custom Background Color */
		body {
			background-color: %1$s;
		}
		
	';

	wp_add_inline_style( 'bytemonkey-style', sprintf( $css, $background_color ) );
}
add_action( 'wp_enqueue_scripts', 'bytemonkey_background_color_css', 11 );

/**
 * Enqueues front-end CSS for the main text color.
 *
 * @see wp_add_inline_style()
 */
function bytemonkey_main_text_color_css() {
	$color_scheme          = bytemonkey_get_color_scheme();
	$default_color         = $color_scheme[3];
	$main_text_color 	   = get_theme_mod( 'bytemonkey_main_text_color', $default_color );

	// Don't do anything if the current color is the default.
	if ( $main_text_color === $default_color ) {
		return;
	}

	$css = '
		/* Main Text Color */
		body,
		.entry-meta a,
		input[type="text"],
		input[type="email"],
		input[type="tel"],
		input[type="url"],
		input[type="password"],
		input[type="search"],
		textarea,
		#secondary .widget,
		#secondary .widget > h3,
		.dropdown-menu > li > a,
		#footer-area .footer-widget-area p,
		.site-info a:hover,
		.site-description {
			color: %1$s;
		}
		
		#footer-area .social-icons a,
		#footer-area .social-icons li a {
			background-color: %1$s;
		}
		
		.comment-reply-link,
		.scroll-to-top {
			background: %1$s;
		}
		
		.comment-reply-link {
			border: #%1$s;
		}
	';

	wp_add_inline_style( 'bytemonkey-style', sprintf( $css, $main_text_color ) );
}
add_action( 'wp_enqueue_scripts', 'bytemonkey_main_text_color_css', 11 );

/**
 * Enqueues front-end CSS for the header text color.
 *
 * @see wp_add_inline_style()
 */
function bytemonkey_header_text_color_css() {
	$color_scheme          = bytemonkey_get_color_scheme();
	$default_color         = $color_scheme[4];
	$header_text_color 	   = get_theme_mod( 'bytemonkey_header_text_color', $default_color );

	// Don't do anything if the current color is the default.
	if ( $header_text_color === $default_color ) {
		return;
	}

	$css = '
		/* Header Text Color */

		.page-links span,
		.page-links a:hover span,
		.pagination>.active>a,
		.pagination>.active>span,
		.pagination>.active>a:hover,
		.pagination>.active>span:hover,
		.pagination>.active>a:focus,
		.pagination>.active>span:focus,
		.well,
		.btn-default:hover,
		.label-default[href]:hover,
		.label-default[href]:focus,
		.btn-default:hover, .btn-default:focus,
		.btn-default:active,
		.btn-default.active,
		#image-navigation .nav-previous a:hover,
		#image-navigation .nav-next a:hover,
		.btn.btn-default,
		button, .button,
		#infinite-handle span:hover,
		.post-navigation a:hover,
		.paging-navigation a:hover,
		.dropdown-menu > li > a:hover,
		.dropdown-menu > li > a:focus,
		.navbar-default .navbar-nav .open .dropdown-menu > li > a:hover,
		.navbar-default .navbar-nav .open .dropdown-menu > li > a:focus,
		.navbar-default .navbar-nav .open .dropdown-menu > li.active > a,
		.dropdown-menu>.active>a,
		.dropdown-menu>.active>a:hover,
		.dropdown-menu>.active>a:focus,
		.navigation .wp-pagenavi-pagination span.current,
		.navigation .wp-pagenavi-pagination a:hover,
		#image-navigation .nav-previous a:hover,
		#image-navigation .nav-next a:hover,
		.gallery-caption,
		#footer-area a:hover,
		.scroll-to-top,
		.site-info,
		.site-info a:hover,
		.site-info a:focus,
		#secondary .widget .social-icons a,
		#footer-area .social-icons a,
		#footer-area .social-icons li a,
		.cfa-button:hover,
		.navbar-default .navbar-nav > li > a {
			color: %1$s;
		}

		.post-navigation a,
		.paging-navigation a,
		.post-inner-content,
		.well,
		.post-inner-content {
			background-color: %1$s;
		}
		
		@media (max-width: 767px) {
			.navbar-default .navbar-nav .open .dropdown-menu>.active>a,
			.navbar-default .navbar-nav .open .dropdown-menu>.active>a:focus,
			.navbar-default .navbar-nav .open .dropdown-menu>.active>a:hover {
				color: %1$s;
			}
		}

	';

	wp_add_inline_style( 'bytemonkey-style', sprintf( $css, $header_text_color ) );
}
add_action( 'wp_enqueue_scripts', 'bytemonkey_header_text_color_css', 11 );

/**
 * Enqueues front-end CSS for the header hover color.
 *
 * @see wp_add_inline_style()
 */
function bytemonkey_header_hover_color_css() {
	$color_scheme          = bytemonkey_get_color_scheme();
	$default_color         = $color_scheme[5];
	$header_hover_color    = get_theme_mod( 'bytemonkey_header_hover_color', $default_color );

	// Don't do anything if the current color is the default.
	if ( $header_hover_color === $default_color ) {
		return;
	}

	$css = '
		/* Header Hover Color */
		.navbar-default .navbar-nav > li > a:hover, 
		.navbar-default .navbar-nav > .active > a, 
		.navbar-default .navbar-nav > .active > a:hover, 
		.navbar-default .navbar-nav > .active > a:focus, 
		.navbar-default .navbar-nav > li > a:hover, 
		.navbar-default .navbar-nav > li > a:focus, 
		.navbar-default .navbar-nav > .open > a, 
		.navbar-default .navbar-nav > .open > a:hover, 
		.navbar-default .navbar-nav > .open > a:focus,
		.navbar-default .navbar-nav .current-menu-ancestor a.dropdown-toggle,
		#footer-area a,
		.site-info a,
		#secondary .widget .post-content a:hover,
		.navbar > .container .navbar-brand,
		.navbar-default .navbar-nav .open .dropdown-menu > li > a {
			color: %1$s;
		}
		
		.social-icons a,
		.post-inner-content,
		.social-icons li a,
		.popular-posts-wrapper .post .post-image,
		.tagcloud a {
			background-color: %1$s;
		}
		
		.post-inner-content,
		.well,
		#infinite-handle span,
		.post-navigation a,
		.paging-navigation a,
		.wp-caption {
			border: 1px solid %1$s;
		}
		
		cfa,
		.post-inner-content:first-child,
		#colophon {
			border-top: 1px solid %1$s;
		}
		
		.comment .comment-body,
		#footer-area ul li {
			border-bottom: 1px solid %1$s;
		}
		
		.comment-list .children {
			border-left: 1px solid %1$s;
		}
		
		.pagination>li>a,
		.pagination>li>span {
			border-color: %1$s;
		}
		
		@media (max-width: 767px) {
			.navbar-default .navbar-nav .open .dropdown-menu>.active>a, 
			.navbar-default .navbar-nav .open .dropdown-menu>.active>a:focus, 
			.navbar-default .navbar-nav .open .dropdown-menu>.active>a:hover {
				color: %1$s;
			}
		}

	';

	wp_add_inline_style( 'bytemonkey-style', sprintf( $css, $header_hover_color ) );
}
add_action( 'wp_enqueue_scripts', 'bytemonkey_header_hover_color_css', 11 );

/**
 * Enqueues front-end CSS for the footer widget background color.
 *
 * @see wp_add_inline_style()
 */
function bytemonkey_footer_widget_bg_color_css() {
	$color_scheme          	= bytemonkey_get_color_scheme();
	$default_color         	= $color_scheme[6];
	$footer_widget_bg_color	= get_theme_mod( 'bytemonkey_footer_widget_bg_color', $default_color );

	// Don't do anything if the current color is the default.
	if ( $footer_widget_bg_color === $default_color ) {
		return;
	}

	$css = '
		/* Footer Widget Background Color */
		
		#footer-area {
			background-color: %1$s;
		}
		
		hr.section-divider {
			border-color: %1$s;
		}
		
		input[type="text"],
		input[type="email"],
		input[type="tel"],
		input[type="url"],
		input[type="password"],
		input[type="search"],
		textarea,
		.page-links a span,
		.comment .comment-body 
		#image-navigation .nav-previous a,
		#image-navigation .nav-next a {
			border: 1px solid %1$s;
		}
		
	';

	wp_add_inline_style( 'bytemonkey-style', sprintf( $css, $footer_widget_bg_color ) );
}
add_action( 'wp_enqueue_scripts', 'bytemonkey_footer_widget_bg_color_css', 11 );

/**
	* Enqueues front-end CSS for the main text typography color.
 *
 * @see wp_add_inline_style()
 */
function bytemonkey_main_typography_css() {
	$typography_defaults   = bytemonkey_get_typography_defaults();
	$default_face          = $typography_defaults['main_face'];
	$default_size		   = $typography_defaults['main_size'];
	$default_style		   = $typography_defaults['main_style'];
	
	$main_face			   = get_theme_mod('bytemonkey_main_typography_face', $default_face);
	$main_size			   = get_theme_mod('bytemonkey_main_typography_size', $default_size);
	$main_style			   = get_theme_mod('bytemonkey_main_typography_style', $default_style);
	
	// Don't do anything if the current typography is the default.
	if ( $main_face === $default_face && $main_size === $default_size && $main_style === $default_style ) {
		return;
	}

	$css = '
		/* Custom Primary Typography */
		body,
		.navbar.navbar-default,
		.entry-content {
			font-family: \'%1$s\';
		}
		
		body,
		.entry-content,
		#footer-area .footer-widget-area a,
		#secondary .widget a {
			font-size: %2$s;
		}
		
		body,
		.entry-content {
			font-weight: %3$s;	
		}
	';

	wp_add_inline_style( 'bytemonkey-style', sprintf( $css, $main_face, $main_size, $main_style ) );
}
add_action( 'wp_enqueue_scripts', 'bytemonkey_main_typography_css', 11 );

/**
	* Enqueues front-end CSS for the header text typography color.
 *
 * @see wp_add_inline_style()
 */
function bytemonkey_header_typography_css() {
	$typography_defaults   = bytemonkey_get_typography_defaults();
	$default_face          = $typography_defaults['head_face'];
	$default_style		   = $typography_defaults['head_style'];
	
	$head_face			   = get_theme_mod('bytemonkey_header_typography_face', $default_face);
	$head_style			   = get_theme_mod('bytemonkey_header_typography_style', $default_style);
	
	// Don't do anything if the current color is the default.
	if ( $head_face === $default_face && $head_style === $default_style ) {
		return;
	}

	$css = '
		/* Custom Header Typography */
		h1,
		h2,
		h3,
		h4,
		h5,
		h6,
		.h1,
		.h2,
		.h3,
		.h4,
		.h5,
		.h6,
		.navbar > .container .navbar-brand {
			font-family: \'%1$s\';
		}
		
		h1,
		h2,
		h3,
		h4,
		h5,
		h6,
		.h1,
		.h2,
		.h3,
		.h4,
		.h5,
		.h6 {
			font-weight: %2$s;	
		}
	';

	wp_add_inline_style( 'bytemonkey-style', sprintf( $css, $head_face, $head_style ) );
}
add_action( 'wp_enqueue_scripts', 'bytemonkey_header_typography_css', 11 );

/**
	* Enqueues front-end CSS for the widget header typography color.
 *
 * @see wp_add_inline_style()
 */
function bytemonkey_widget_typography_css() {
	$typography_defaults   = bytemonkey_get_typography_defaults();
	$default_face          = $typography_defaults['widg_face'];
	$default_size		   = $typography_defaults['widg_size'];
	$default_style		   = $typography_defaults['widg_style'];
	
	$widg_face			   = get_theme_mod('bytemonkey_widget_typography_face', $default_face);
	$widg_size			   = get_theme_mod('bytemonkey_widget_typography_size', $default_size);
	$widg_style			   = get_theme_mod('bytemonkey_widget_typography_style', $default_style);
	

	// Don't do anything if the current typography is the default.
	if ( $widg_face === $default_face && $widg_size === $widg_size && $widg_style === $widg_style ) {
		return;
	}

	$css = '
		/* Widget Header Typography */
		#secondary .widget > h3,
		#footer-area .widgettitle,
		.widgettitle {
			font-family: \'%1$s\';
		}
		
		#secondary .widget > h3 
		#footer-area .widgettitle,
		.widgettitle {
			font-size: %2$s;
		}
		
		#secondary .widget > h3
		#footer-area .widgettitle,
		.widgettitle {
			font-weight: %3$s;	
		}
	';

	wp_add_inline_style( 'bytemonkey-style', sprintf( $css, $widg_face, $widg_size, $widg_style ) );
}
add_action( 'wp_enqueue_scripts', 'bytemonkey_widget_typography_css', 11 );

/*************
 * Sanitize helper functions below 
 */

/**
 * Handles sanitization for Bytemonkey color schemes.
 *
 * @param string $value Color scheme name value.
 * @return string Color scheme name.
 */
function bytemonkey_sanitize_color_scheme( $value ) {
	$color_schemes = bytemonkey_get_color_scheme_choices();

	if ( ! array_key_exists( $value, $color_schemes ) ) {
		return 'default';
	}

	return $value;
} 

/**
 * Sanitzie checkbox for WordPress customizer
 */
function bytemonkey_sanitize_checkbox( $input ) {
    if ( $input == 1 ) {
        return 1;
    } else {
        return '';
    }
}
/**
 * Adds sanitization callback function: colors
 * @package bytemonkey
 */
function bytemonkey_sanitize_hexcolor($color) {
    if ($unhashed = sanitize_hex_color_no_hash($color))
        return '#' . $unhashed;
    return $color;
}

/**
 * Adds sanitization callback function: Nohtml
 * @package bytemonkey
 */
function bytemonkey_sanitize_nohtml($input) {
    return wp_filter_nohtml_kses($input);
}

/**
 * Adds sanitization callback function: Number
 * @package bytemonkey
 */
function bytemonkey_sanitize_number($input) {
    if ( isset( $input ) && is_numeric( $input ) ) {
        return $input;
    }
}

/**
 * Adds sanitization callback function: Strip Slashes
 * @package bytemonkey
 */
function bytemonkey_sanitize_strip_slashes($input) {
    return wp_kses_stripslashes($input);
}

/**
 * Adds sanitization callback function: Sanitize Text area
 * @package bytemonkey
 */
function bytemonkey_sanitize_textarea($input) {
    return sanitize_text_field($input);
}

/**
 * Adds sanitization callback function: Sidebar Layout
 * @package bytemonkey
 */
function bytemonkey_sanitize_layout( $input ) {
    $site_layout = bytemonkey_get_layout_options();
    if ( array_key_exists( $input, $site_layout ) ) {
        return $input;
    } else {
        return '';
    }
}

/**
 * Adds sanitization callback function: Typography Size
 * @package bytemonkey
 */
function bytemonkey_sanitize_typo_size( $input ) {
    $typography_options = bytemonkey_get_typography_options();
	$typography_defaults = bytemonkey_get_typography_defaults();
    if ( array_key_exists( $input, $typography_options['sizes'] ) ) {
        return $input;
    } else {
        return $typography_defaults['size'];
    }
}

/**
 * Adds sanitization callback function: Typography Face
 * @package bytemonkey
 */
function bytemonkey_sanitize_typo_face( $input ) {
    $typography_options = bytemonkey_get_typography_options();
	$typography_defaults = bytemonkey_get_typography_defaults();
    if ( array_key_exists( $input, $typography_options['faces'] ) ) {
        return $input;
    } else {
        return $typography_defaults['face'];
    }
}

/**
 * Adds sanitization callback function: Typography Style
 * @package bytemonkey
 */
function bytemonkey_sanitize_typo_style( $input ) {
    $typography_options = bytemonkey_get_typography_options();
	$typography_defaults = bytemonkey_get_typography_defaults();
    if ( array_key_exists( $input, $typography_options['styles'] ) ) {
        return $input;
    } else {
        return $typography_defaults['style'];
    }
}
