<?php

if ( ! function_exists( 'monticello_light_support' ) ) :
	function monticello_light_support()  {

		// Enqueue editor styles.
		add_editor_style( array( 
			monticello_light_fonts_url(),
			get_template_directory() . '/assets/ponyfill.css',
			get_stylesheet_uri()
		) );

    }
    add_action( 'after_setup_theme', 'monticello_light_support' );
endif;

/**
 * Add Google webfonts, if necessary.
 *
 * - See: http://themeshaper.com/2014/08/13/how-to-add-google-fonts-to-wordpress-themes/
 */
function monticello_light_fonts_url() {

	$font_families = array();

	$font_families[] = 'Work Sans:300,400i,400,500,500i';

	$query_args = array(
		'family' => urlencode( implode( '|', $font_families ) ),
		'subset' => urlencode( 'latin,latin-ext' ),
	);

	$fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );

	return esc_url_raw( $fonts_url );
}
/**
 * 
 * Enqueue scripts and styles.
 */
function monticello_light_scripts() {
	wp_enqueue_style( 'monticello-light-fonts', monticello_light_fonts_url(), array(), null );
	wp_enqueue_style( 'monticello-light-style', get_stylesheet_uri(), array('ponyfill-ponyfill'), wp_get_theme()->get( 'Version' )  );
}
add_action( 'wp_enqueue_scripts', 'monticello_light_scripts', 11 );
