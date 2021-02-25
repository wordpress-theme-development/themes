<?php
if ( ! function_exists( 'ponyfill_support' ) ) :
	function ponyfill_support()  {

		// Alignwide and alignfull classes in the block editor.
		add_theme_support( 'align-wide' );

		// Adding support for responsive embedded content.
		add_theme_support( 'responsive-embeds' );

		// Add support for experimental link color control.
		add_theme_support( 'experimental-link-color' );

		// Add support for editor styles.
		add_theme_support( 'editor-styles' );

		add_theme_support( 'wp-block-styles' );

		// Enqueue editor styles.
		add_editor_style( array( 
			'style.css'
		) );
    }
    add_action( 'after_setup_theme', 'ponyfill_support' );
endif;

/**
 * 
 * Enqueue scripts and styles.
 */
function ponyfill_scripts() {
	// Enqueue the ponyfill from which the Theme derives its name.
	wp_enqueue_style( 'ponyfill-ponyfill', get_template_directory() . '/assets/ponyfill.css', array(), wp_get_theme()->get( 'Version' )  );
}
add_action( 'wp_enqueue_scripts', 'global_styles_scripts', 11 );