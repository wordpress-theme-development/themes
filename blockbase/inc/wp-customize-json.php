<?php

require get_template_directory() . '/inc/wp-customize-json-control.php';

class GlobalStylesJSONCustomizer {

	private $section_key = 'customize-global-styles-json';

	function __construct() {
		add_action( 'customize_register', array( $this, 'initialize' ) );
	}

	function initialize( $wp_customize ) {

		$user_custom_post_type_id     = WP_Theme_JSON_Resolver_Gutenberg::get_user_custom_post_type_id();
		$user_theme_json_post         = get_post( $user_custom_post_type_id );
		$user_theme_json_post_content = json_decode( $user_theme_json_post->post_content );

		$theme = wp_get_theme();

		$wp_customize->add_section(
			$this->section_key,
			array(
				'capability'  => 'edit_theme_options',
				'description' => sprintf( __( 'JSON Customization for %1$s', 'blockbase' ), $theme->name ),
				'title'       => __( 'Global Styles JSON', 'blockbase' ),
			)
		);

		$wp_customize->add_setting(
			$this->section_key,
			array(
				'type'    => 'option',
				'default' => json_encode( json_decode( $user_theme_json_post->post_content ), JSON_PRETTY_PRINT ),
			)
		);

		$wp_customize->add_control(
			new WP_Customize_JSON_Editor_Control(
				$wp_customize,
				$this->section_key,
				array(
					'label'   => 'Custom JSON',
					'section' => $this->section_key,
				)
			)
		);
	}

}

new GlobalStylesJSONCustomizer;
