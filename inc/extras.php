<?php
/**
 * Custom functions that act independently of the theme templates
 *
 * Eventually, some of the functionality here could be replaced by core features
 *
 * @package bytemonkey
 */



/**
 * Password protected post form using Boostrap classes
 */
add_filter( 'the_password_form', 'custom_password_form' );

function custom_password_form() {
	global $post;
	$label = 'pwbox-'.( empty( $post->ID ) ? rand() : $post->ID );
	$o = '<form class="protected-post-form" action="' . get_option('siteurl') . '/wp-login.php?action=postpass" method="post">
	<div class="row">
		<div class="col-lg-10">
			<p>' . esc_html__( "This post is password protected. To view it please enter your password below:" ,'bytemonkey') . '</p>
			<label for="' . $label . '">' . esc_html__( "Password:" ,'bytemonkey') . ' </label>
			<div class="input-group">
				<input class="form-control" value="' . get_search_query() . '" name="post_password" id="' . $label . '" type="password">
				<span class="input-group-btn"><button type="submit" class="btn btn-default" name="submit" id="searchsubmit" value="' . esc_attr__( "Submit",'bytemonkey' ) . '">' . esc_html__( "Submit" ,'bytemonkey') . '</button></span>
			</div>
		</div>
	</div>
	</form>';
	return $o;
}

// Add Bootstrap classes for table
add_filter( 'the_content', 'bytemonkey_add_custom_table_class' );
function bytemonkey_add_custom_table_class( $content ) {
	return preg_replace( '/(<table) ?(([^>]*)class="([^"]*)")?/', '$1 $3 class="$4 table table-hover" ', $content);
}


if ( ! function_exists( 'bytemonkey_social_icons' ) ) :
/**
 * Display social links in footer and widgets
 *
 * @package bytemonkey
 */
function bytemonkey_social_icons(){
	if ( has_nav_menu( 'social-menu' ) ) {
		wp_nav_menu(
			array(
				'theme_location'  => 'social-menu',
				'container'       => 'nav',
				'container_id'    => 'menu-social',
				'container_class' => 'social-icons',
				'menu_id'         => 'menu-social-items',
				'menu_class'      => 'social-menu',
				'depth'           => 1,
				'fallback_cb'     => '',
			'link_before'     	  => '<i class="social_icon fa"><span>',
			'link_after'      	  => '</span></i>'
			)
		);
	}
}
endif;

if ( ! function_exists( 'bytemonkey_header_menu' ) ) :
/**
 * Header menu (should you choose to use one)
 */
function bytemonkey_header_menu() {
	// display the WordPress Custom Menu if available
	wp_nav_menu(array(
		'menu'              => 'primary',
		'theme_location'    => 'primary',
		'depth'             => 2,
		'container'         => 'div',
		'container_class'   => 'collapse navbar-collapse navbar-ex1-collapse',
		'menu_class'        => 'nav navbar-nav',
		'fallback_cb'       => 'wp_bootstrap_navwalker::fallback',
		'walker'            => new wp_bootstrap_navwalker()
	));
} /* end header menu */
endif;

if ( ! function_exists( 'bytemonkey_footer_links' ) ) :
/**
 * Footer menu (should you choose to use one)
 */
function bytemonkey_footer_links() {
	// display the WordPress Custom Menu if available
	wp_nav_menu(array(
		'container'       => '',                              // remove nav container
		'container_class' => 'footer-links clearfix',   // class of container (should you choose to use it)
		'menu'            => esc_html__( 'Footer Links', 'bytemonkey' ),   // nav name
		'menu_class'      => 'nav footer-nav clearfix',      // adding custom nav class
		'theme_location'  => 'footer-links',             // where it's located in the theme
		'before'          => '',                                 // before the menu
		'after'           => '',                                  // after the menu
		'link_before'     => '',                            // before each link
		'link_after'      => '',                             // after each link
		'depth'           => 0,                                   // limit the depth of the nav
		'fallback_cb'     => 'bytemonkey_footer_links_fallback'  // fallback function
	));
} /* end bytemonkey footer link */
endif;


if ( ! function_exists( 'bytemonkey_call_for_action' ) ) :
/**
 * Call for action text and button displayed above content
 */
function bytemonkey_call_for_action() {
	if ( is_front_page() && get_theme_mod( 'bytemonkey_w2f_cfa_text', '' ) != '' ){
		echo '<div class="cfa">';
		echo '<div class="container">';
        echo '<div class="col-sm-8">';
        echo '<span class="cfa-text">'. get_theme_mod( 'bytemonkey_w2f_cfa_text', '' ).'</span>';
        echo '</div>';
        echo '<div class="col-sm-4">';
        echo '<a class="btn btn-lg cfa-button" href="'. get_theme_mod( 'bytemonkey_w2f_cfa_link', '' ). '">'. get_theme_mod( 'bytemonkey_w2f_cfa_button', '' ). '</a>';
        echo '</div>';
		echo '</div>';
		echo '</div>';
	}
}
endif;

/**
 * function to show the footer info, copyright information
 */
function bytemonkey_footer_info() {
global $bytemonkey_footer_info;
	$year = date("Y");
	printf( esc_html__( '&#169; %1$s | Powered by %2$s', 'bytemonkey' ) , $year, '<a href="http://wordpress.org/" target="_blank">WordPress</a>');
}

/**
 * Add Bootstrap thumbnail styling to images with captions
 * Use <figure> and <figcaption>
 *
 * @link http://justintadlock.com/archives/2011/07/01/captions-in-wordpress
 */
function bytemonkey_caption($output, $attr, $content) {
	if (is_feed()) {
		return $output;
	}

	$defaults = array(
		'id'      => '',
		'align'   => 'alignnone',
		'width'   => '',
		'caption' => ''
	);

	$attr = shortcode_atts($defaults, $attr);

	// If the width is less than 1 or there is no caption, return the content wrapped between the [caption] tags
	if ($attr['width'] < 1 || empty($attr['caption'])) {
		return $content;
	}

	// Set up the attributes for the caption <figure>
	$attributes  = (!empty($attr['id']) ? ' id="' . esc_attr($attr['id']) . '"' : '' );
	$attributes .= ' class="thumbnail wp-caption ' . esc_attr($attr['align']) . '"';
	$attributes .= ' style="width: ' . (esc_attr($attr['width']) + 10) . 'px"';

	$output  = '<figure' . $attributes .'>';
	$output .= do_shortcode($content);
	$output .= '<figcaption class="caption wp-caption-text">' . $attr['caption'] . '</figcaption>';
	$output .= '</figure>';

	return $output;
}
add_filter('img_caption_shortcode', 'bytemonkey_caption', 10, 3);

/**
 * Skype URI support for social media icons
 */
function bytemonkey_allow_skype_protocol( $protocols ){
    $protocols[] = 'skype';
    return $protocols;
}
add_filter( 'kses_allowed_protocols' , 'bytemonkey_allow_skype_protocol' );

/**
 * Fallback option for the old Social Icons.
 */
function bytemonkey_social(){
	if( get_theme_mod('bytemonkey_footer_social_icons', 0)  ) {
		bytemonkey_social_icons();
	}
}

/**
 * Adds the URL to the top level navigation menu item
 */
function  bytemonkey_add_top_level_menu_url( $atts, $item, $args ){
	if ( !wp_is_mobile() && isset($args->has_children) && $args->has_children  ) {
		$atts['href'] = ! empty( $item->url ) ? $item->url : '';
	}
	return $atts;
}
add_filter( 'nav_menu_link_attributes', 'bytemonkey_add_top_level_menu_url', 99, 3 );

/**
	* Makes the top level navigation menu item clickable
 */
function bytemonkey_make_top_level_menu_clickable(){
	if ( !wp_is_mobile() ) { ?>
		<script type="text/javascript">
			jQuery( document ).ready( function( $ ){
				if ( $( window ).width() >= 767 ){
					$( '.navbar-nav > li.menu-item > a' ).click( function(){
						window.location = $( this ).attr( 'href' );
					});
				}
			});
		</script>
<?php }
}
add_action('wp_footer', 'bytemonkey_make_top_level_menu_clickable', 1);