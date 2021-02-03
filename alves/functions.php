<?php
/**
 * Child Theme Functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package WordPress
 * @subpackage Alves
 * @since 1.0.0
 */

if ( ! function_exists( 'varia_default_colors' ) ) {
	function varia_default_colors() {
		return array(
			'background' => '#ffffff',
			'foreground' => '#394d55',
			'primary'    => '#3E7D98',
			'secondary'  => '#9B6A36',
			'tertiary'   => '#3E7D98',
		);
	}
}

if ( ! function_exists( 'alves_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function alves_setup() {

		// Add child theme editor styles, compiled from `style-child-theme-editor.scss`.
		add_editor_style( 'style-editor.css' );

		// Add child theme editor font sizes to match Sass-map variables in `_config-child-theme-deep.scss`.
		add_theme_support(
			'editor-font-sizes',
			array(
				array(
					'name'      => __( 'Small', 'alves' ),
					'shortName' => __( 'S', 'alves' ),
					'size'      => 16.6667,
					'slug'      => 'small',
				),
				array(
					'name'      => __( 'Normal', 'alves' ),
					'shortName' => __( 'M', 'alves' ),
					'size'      => 20,
					'slug'      => 'normal',
				),
				array(
					'name'      => __( 'Large', 'alves' ),
					'shortName' => __( 'L', 'alves' ),
					'size'      => 28.8,
					'slug'      => 'large',
				),
				array(
					'name'      => __( 'Huge', 'alves' ),
					'shortName' => __( 'XL', 'alves' ),
					'size'      => 34.5667,
					'slug'      => 'huge',
				),
			)
		);

		// Setup nav on side toggle support.
		if ( function_exists( 'varia_mobile_nav_on_side_setup' ) ) {
			varia_mobile_nav_on_side_setup();
		}
	}
endif;
add_action( 'after_setup_theme', 'alves_setup', 12 );

/**
 * Register second and third footer widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function alves_widgets_init() {

	register_sidebar(
		array(
			'name'          => __( 'Footer 2', 'alves' ),
			'id'            => 'sidebar-2',
			'description'   => __( 'Add widgets here to appear in your footer.', 'alves' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);

	register_sidebar(
		array(
			'name'          => __( 'Footer 3', 'alves' ),
			'id'            => 'sidebar-3',
			'description'   => __( 'Add widgets here to appear in your footer.', 'alves' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);

}
add_action( 'widgets_init', 'alves_widgets_init', 12 );


/**
 * Filter the content_width in pixels, based on the child-theme's design and stylesheet.
 */
function alves_content_width() {
	return 750;
}
add_filter( 'varia_content_width', 'alves_content_width' );

/**
 * Add Google webfonts, if necessary
 *
 * - See: http://themeshaper.com/2014/08/13/how-to-add-google-fonts-to-wordpress-themes/
 */
function alves_fonts_url() {

	$fonts_url = '';

	/* Translators: If there are characters in your language that are not
	* supported by Lora, translate this to 'off'. Do not translate
	* into your own language.
	*/
	$lora = esc_html_x( 'on', 'Lora font: on or off', 'alves' );

	/* Translators: If there are characters in your language that are not
	* supported by Karla, translate this to 'off'. Do not translate
	* into your own language.
	*/
	$karla = esc_html_x( 'on', 'Karla font: on or off', 'alves' );

	if ( 'off' !== $lora || 'off' !== $karla ) {
		$font_families = array();

		if ( 'off' !== $lora ) {
			$font_families[] = 'Lora:400,700,400italic,700italic';
		}

		if ( 'off' !== $karla ) {
			$font_families[] = 'Karla:400,700,400italic,700italic';
		}

		$query_args = array(
			'family' => urlencode( implode( '|', $font_families ) ),
			'subset' => urlencode( 'latin,latin-ext' ),
		);

		$fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
	}

	return esc_url_raw( $fonts_url );
}

/**
 * Enqueue scripts and styles.
 */
function alves_scripts() {

	// enqueue Google fonts, if necessary
	 wp_enqueue_style( 'alves-fonts', alves_fonts_url(), array(), null );

	// dequeue parent styles
	wp_dequeue_style( 'varia-style' );

	// enqueue child styles
	wp_enqueue_style( 'alves-style', get_stylesheet_uri(), array(), wp_get_theme()->get( 'Version' ) );

	// enqueue child RTL styles
	wp_style_add_data( 'alves-style', 'rtl', 'replace' );

}
add_action( 'wp_enqueue_scripts', 'alves_scripts', 99 );

/**
 * Enqueue theme styles for the block editor.
 */
function alves_editor_styles() {

	// Enqueue Google fonts in the editor, if necessary
	wp_enqueue_style( 'alves-editor-fonts', alves_fonts_url(), array(), null );

}
add_action( 'enqueue_block_editor_assets', 'alves_editor_styles' );
