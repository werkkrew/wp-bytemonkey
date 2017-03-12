<?php
/**
 * Bytemonkey functions and definitions
 *
 * Set up the theme and provides some helper functions, which are used in the
 * theme as custom template tags. Others are attached to action and filter
 * hooks in WordPress to change core functionality.
 *
 * When using a child theme you can override certain functions (those wrapped
 * in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before
 * the parent theme's file, so the child theme functions would be used.
 *
 * @link https://codex.wordpress.org/Theme_Development
 * @link https://codex.wordpress.org/Child_Themes
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are
 * instead attached to a filter or action hook.
 *
 * For more information on hooks, actions, and filters,
 * {@link https://codex.wordpress.org/Plugin_API}
 *
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 648; /* pixels */
}

# Jetpack tiled gallery content width
function bytemonkey_custom_tiled_gallery_width() {
    return '1050';
}
add_filter( 'tiled_gallery_content_width', 'bytemonkey_custom_tiled_gallery_width' );

<<<<<<< HEAD
=======

>>>>>>> efd695fcfafe474133e39cf55f2920f446de085c
/**
 * Set excerpt length 
 */
function wpdocs_custom_excerpt_length( $length ) {
	$excerpt_length = get_theme_mod('bytemonkey_excerpt_length', 150);
    return $excerpt_length;
}
add_filter( 'excerpt_length', 'wpdocs_custom_excerpt_length', 999 );

/**
 * Set the content width for full width pages with no sidebar.
 */
function bytemonkey_content_width() {
	if ( is_page_template( 'page-fullwidth.php' ) ) {
		global $content_width;
		$content_width = 1008; /* pixels */
	}
}
add_action( 'template_redirect', 'bytemonkey_content_width' );

function bytemonkey_fonts_url() {

        $google_fonts_url = 'https://fonts.googleapis.com/css?family=';

        $default_face = bytemonkey_get_typography_defaults();

        $google_fonts = array ('Noto Sans' => 'Noto+Sans:400,400i,700,700i',
							   'Noto Serif' => 'Noto+Serif:400,400i,700,700i',
							   'Roboto Slab' => 'Roboto+Slab:400,300,700',
							   'Open Sans' => 'Open+Sans:400italic,400,600,700',
							   'Source Sans Pro' => 'Source+Sans+Pro:400,600,400italic');

        $theme_fonts = array ( get_theme_mod('bytemonkey_main_typography_face', $default_face['main_face']),
							   get_theme_mod('bytemonkey_header_typography_face', $default_face['head_face']),
							   get_theme_mod('bytemonkey_widget_typography_face', $default_face['widg_face']));

        $theme_fonts = array_unique($theme_fonts);
		
        foreach($theme_fonts as $font){
			$fonts[] = $google_fonts[$font];
        }
<<<<<<< HEAD

=======
		
>>>>>>> efd695fcfafe474133e39cf55f2920f446de085c
        $fonts = implode("|", $fonts);

        $bytemonkey_font_url = $google_fonts_url . $fonts;

        return $bytemonkey_font_url;
}


if ( ! function_exists( 'bytemonkey_main_content_bootstrap_classes' ) ) :
/**
 * Add Bootstrap classes to the main-content-area wrapper.
 */
function bytemonkey_main_content_bootstrap_classes() {
	if ( is_page_template( 'page-fullwidth.php' ) ) {
		return 'col-sm-12 col-md-12';
	}
	return 'col-sm-12 col-md-8';
}
endif; // bytemonkey_main_content_bootstrap_classes

if ( ! function_exists( 'bytemonkey_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function bytemonkey_setup() {

	/*
	* Make theme available for translation.
	* Translations can be filed in the /languages/ directory.
	*/
	load_theme_textdomain( 'bytemonkey', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/**
	* Enable support for Post Thumbnails on posts and pages.
	*
	* @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	*/
	add_theme_support( 'post-thumbnails' );

	add_image_size( 'bytemonkey-featured', 750, 410, true );
	add_image_size( 'tab-small', 60, 60 , true); // Small Thumbnail

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
	'primary'      => esc_html__( 'Primary Menu', 'bytemonkey' ),
	'footer-links' => esc_html__( 'Footer Links', 'bytemonkey' ) // secondary nav in footer
	) );

	// Enable support for Post Formats.
	add_theme_support( 'post-formats', array( 'aside', 'image', 'video', 'quote', 'link' ) );

	// Enable support for jetpack social buttons
	add_theme_support( 'social-links', array( 'facebook', 'twitter', 'linkedin', 'google_plus', 'tumblr') );

	// Enable support for HTML5 markup.
	add_theme_support( 'html5', 
						array(
							'comment-list',
							'search-form',
							'comment-form',
							'gallery',
							'caption',
	));

	/*
	* Let WordPress manage the document title.
	* By adding theme support, we declare that this theme does not use a
	* hard-coded <title> tag in the document head, and expect WordPress to
	* provide it for us.
	*/
	add_theme_support( 'title-tag' );
	
	/*
	 * This theme styles the visual editor to resemble the theme style,
	 * specifically font, colors, icons, and column width.
	 */
	add_editor_style( array( 'css/editor-style.css', bytemonkey_fonts_url() ) );

}
endif; // bytemonkey_setup
add_action( 'after_setup_theme', 'bytemonkey_setup' );

/**
 * Register widgetized area and update sidebar with default widgets.
 */
function bytemonkey_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'bytemonkey' ),
		'id'            => 'sidebar-1',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	));

	register_sidebar(array(
		'id'            => 'footer-widget-1',
		'name'          =>  esc_html__( 'Footer Widget 1', 'bytemonkey' ),
		'description'   =>  esc_html__( 'Used for footer widget area', 'bytemonkey' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3 class="widgettitle">',
		'after_title'   => '</h3>',
	));

	register_sidebar(array(
		'id'            => 'footer-widget-2',
		'name'          =>  esc_html__( 'Footer Widget 2', 'bytemonkey' ),
		'description'   =>  esc_html__( 'Used for footer widget area', 'bytemonkey' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3 class="widgettitle">',
		'after_title'   => '</h3>',
	));

	register_sidebar(array(
		'id'            => 'footer-widget-3',
		'name'          =>  esc_html__( 'Footer Widget 3', 'bytemonkey' ),
		'description'   =>  esc_html__( 'Used for footer widget area', 'bytemonkey' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3 class="widgettitle">',
		'after_title'   => '</h3>',
	));

}
add_action( 'widgets_init', 'bytemonkey_widgets_init' );


/**
 * This function removes inline styles set by WordPress gallery.
 */
function bytemonkey_remove_gallery_css( $css ) {
	return preg_replace( "#<style type='text/css'>(.*?)</style>#s", '', $css );
}

add_filter( 'gallery_style', 'bytemonkey_remove_gallery_css' );

// Typography Options
function bytemonkey_get_typography_options() {

	$typography_options = array(
        'sizes' => array( '6px' => '6px','10px' => '10px','12px' => '12px','14px' => '14px','15px' => '15px','16px' => '16px','18'=> '18px','20px' => '20px','24px' => '24px','28px' => '28px','32px' => '32px','36px' => '36px','42px' => '42px','48px' => '48px' ),
        'faces' => array(
				'Noto Sans'      => 'Noto Sans',
				'Noto Serif'     => 'Noto Serif',
                'Source Sans Pro'=> 'Source Sans Pro',
				'Roboto Slab'    => 'Roboto Slab',
				'Open Sans'      => 'Open Sans',
				'arial'          => 'Arial',
                'verdana'        => 'Verdana, Geneva',
                'trebuchet'      => 'Trebuchet',
                'georgia'        => 'Georgia',
                'times'          => 'Times New Roman',
                'tahoma'         => 'Tahoma, Geneva',
                'palatino'       => 'Palatino',
                'helvetica'      => 'Helvetica',
                'Helvetica Neue' => 'Helvetica Neue,Helvetica,Arial,sans-serif'
        ),
        'styles' => array( 
				'400' 			 => 'Normal',
				'700' 			 => 'Bold' 
		),
        'color'  => true);

	return $typography_options;
}

function bytemonkey_get_typography_defaults() {
	
	// Typography Defaults
	$typography_defaults = array(
			'main_face'  => 'Source Sans Pro',
			'main_size'  => '16px',
			'main_style' => 400,
			'head_face'  => 'Roboto Slab',
			'head_style' => 700,
			'widg_face'  => 'Roboto Slab',
			'widg_size'  => '16px',
			'widg_style' => 700
	);

	return $typography_defaults;
}

function bytemonkey_get_layout_options() {
	
	$layout_options = array(
				'side-pull-left' 	=> esc_html__('Right Sidebar', 'bytemonkey'),
				'side-pull-right' 	=> esc_html__('Left Sidebar', 'bytemonkey'),
				'no-sidebar' 		=> esc_html__('No Sidebar', 'bytemonkey'),
				'full-width' 		=> esc_html__('Full Width', 'bytemonkey'));

	return $layout_options;
}

/**
 * Registers color schemes for Bytemonkey.
 *
 * Can be filtered with {@see 'bytemonkey_color_schemes'}.
 *
 * The order of colors in a colors array:
 * 1. Primary Element Color
 * 2. Secondary Element Color 
 * 3. Background Color
 * 4. Primary Text Color
 * 5. Header/Footer Text Color
 * 6. Header/Footer Hover/Active Color
 * 7. Footer Widget Area Background Color
 *
 *
 * @return array An associative array of color scheme options.
 */
function bytemonkey_get_color_schemes() {
	/**
	 * Filter the color schemes registered for use with Bytemonkey.
	 *
	 * @param array $schemes {
	 *     Associative array of color schemes data.
	 *
	 *     @type array $slug {
	 *         Associative array of information for setting up the color scheme.
	 *
	 *         @type string $label  Color scheme label.
	 *         @type array  $colors HEX codes for default colors prepended with a hash symbol ('#').
	 *     }
	 * }
	 */
	return apply_filters( 'bytemonkey_color_schemes', array(
		'default' => array(
			'label'  => __( 'Default', 'bytemonkey' ),
			'colors' => array(
				'#419eda',
				'#2a6496',
				'#f2f2f2',
				'#6b6b6b',
				'#ffffff',
				'#d6d6d6',
				'#e2e2e2'
			),
		),
		'dark' => array(
			'label'  => __( 'Dark', 'bytemonkey' ),
			'colors' => array(
				'#262626',
				'#1a1a1a',
				'#9adffd',
				'#e5e5e5',
				'#c1c1c1',
				'#d6d6d6',
				'#e2e2e2'				
			),
		)
	) );
}

/**
 * Enqueue scripts and styles.
 */
function bytemonkey_scripts() {

	// Add Bootstrap default CSS
	wp_enqueue_style( 'bytemonkey-bootstrap', get_template_directory_uri() . '/css/bootstrap.min.css' );

	// Add Font Awesome stylesheet
	wp_enqueue_style( 'bytemonkey-icons', get_template_directory_uri() . '/css/font-awesome.min.css' );
	
	// Add Google Fonts
	wp_register_style( 'bytemonkey-fonts', bytemonkey_fonts_url() );
	
	wp_enqueue_style( 'bytemonkey-fonts' );
	
	// Add main theme stylesheet
	wp_enqueue_style( 'bytemonkey-style', get_stylesheet_uri() );

	// Add Modernizr for better HTML5 and CSS3 support
	wp_enqueue_script('bytemonkey-modernizr', get_template_directory_uri().'/js/modernizr.min.js', array('jquery') );

	// Add Bootstrap default JS
	wp_enqueue_script('bytemonkey-bootstrapjs', get_template_directory_uri().'/js/bootstrap.min.js', array('jquery') );

	// Main theme related functions
	wp_enqueue_script( 'bytemonkey-functions', get_template_directory_uri() . '/js/functions.min.js', array('jquery') );

	// This one is for accessibility
	wp_enqueue_script( 'bytemonkey-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20140222', true );

	// Threaded comments
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'bytemonkey_scripts' );

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function bytemonkey_body_classes( $classes ) {
	// Adds a class of group-blog to blogs with more than 1 published author.
	if ( is_multi_author() && (get_theme_mod('bytemonkey_author_block', 1) == 1)) {
		$classes[] = 'group-blog';
	}

	return $classes;
}
add_filter( 'body_class', 'bytemonkey_body_classes' );


// Mark Posts/Pages as Untiled when no title is used
function bytemonkey_title( $title ) {
	if ( $title == '' ) {
		return 'Untitled';
	} else {
		return $title;
	}
}
add_filter( 'the_title', 'bytemonkey_title' );

/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 *
 * @param array $args Configuration arguments.
 * @return array
 */
function bytemonkey_page_menu_args( $args ) {
	$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'bytemonkey_page_menu_args' );

/**
 * Converts a HEX value to RGB.
 *
 * @param string $color The original color, in 3- or 6-digit hexadecimal form.
 * @return array Array containing RGB (red, green, and blue) values for the given
 *               HEX code, empty array otherwise.
 */
function bytemonkey_hex2rgb( $color ) {
	$color = trim( $color, '#' );

	if ( strlen( $color ) === 3 ) {
		$r = hexdec( substr( $color, 0, 1 ).substr( $color, 0, 1 ) );
		$g = hexdec( substr( $color, 1, 1 ).substr( $color, 1, 1 ) );
		$b = hexdec( substr( $color, 2, 1 ).substr( $color, 2, 1 ) );
	} else if ( strlen( $color ) === 6 ) {
		$r = hexdec( substr( $color, 0, 2 ) );
		$g = hexdec( substr( $color, 2, 2 ) );
		$b = hexdec( substr( $color, 4, 2 ) );
	} else {
		return array();
	}

	return array( 'red' => $r, 'green' => $g, 'blue' => $b );
}

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Metabox additions.
 */
require get_template_directory() . '/inc/metaboxes.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';

/**
 * Load custom nav walker
 */
require get_template_directory() . '/inc/navwalker.php';

/**
 * Register Social Icon menu
 */
add_action( 'init', 'register_social_menu' );

function register_social_menu() {
	register_nav_menu( 'social-menu', _x( 'Social Menu', 'nav menu location', 'bytemonkey' ) );
}


/**
* get_layout_class - Returns class name for layout i.e full-width, right-sidebar, left-sidebar etc )
*/
if ( ! function_exists( 'get_layout_class' ) ) :

function get_layout_class () {
    global $post;
	if( is_front_page() ){
		$layout_class = get_theme_mod( 'bytemonkey_frontpage_layout', 'side-pull-left' );
	} elseif ( is_page() ) {
		if( get_post_meta($post->ID, 'site_layout', true) ){
			$layout_class = get_post_meta($post->ID, 'site_layout', true);
		} else {
			$layout_class = get_theme_mod( 'bytemonkey_page_layout', 'side-pull-left' );
		}
	} elseif( is_singular() ) {
		if(	get_post_meta($post->ID, 'site_layout', true) ){
			$layout_class = get_post_meta($post->ID, 'site_layout', true);
		} else {
			$layout_class = get_theme_mod( 'bytemonkey_post_layout', 'full-width' );
		}
    } else {
        $layout_class = get_theme_mod( 'bytemokey_site_layout', 'side-pull-left' );
    }
    return $layout_class;
}

endif;

