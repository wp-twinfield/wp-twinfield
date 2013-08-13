<?php

namespace Pronamic\WP\Twinfield\Settings;

class Settings {

	public function register_settings() {

		/**
		 * ======
		 * API Section
		 * ======
		 */

		add_settings_section(
			'api',
			__( 'API', 'twinfield' ),
			array( $this, 'section_view' ),
			'twinfield-settings'
		);

		add_settings_field(
			'twinfield_username',
			__( 'Username', 'twinfield' ),
			array( $this, 'render_text' ),
			'twinfield-settings',
			'api',
			array( 'label_for' => 'twinfield_username' )
		);

		add_settings_field(
			'twinfield_password',
			__( 'Password', 'twinfield' ),
			array( $this, 'render_password' ),
			'twinfield-settings',
			'api',
			array( 'label_for' => 'twinfield_password' )
		);

		add_settings_field(
			'twinfield_organisation',
			__( 'Organisation', 'twinfield' ),
			array( $this, 'render_text' ),
			'twinfield-settings',
			'api',
			array( 'label_for' => 'twinfield_organisation' )
		);

		add_settings_field(
			'twinfield_office_code',
			__( 'Office Code', 'twinfield' ),
			array( $this, 'render_text' ),
			'twinfield-settings',
			'api',
			array( 'label_for' => 'twinfield_office_code' )
		);

		// Registered settings for the api section
		register_setting( 'wp_twinfield_api', 'twinfield_username' );
		register_setting( 'wp_twinfield_api', 'twinfield_password' );
		register_setting( 'wp_twinfield_api', 'twinfield_organisation' );
		register_setting( 'wp_twinfield_api', 'twinfield_office_code' );

        /**
		 * =========
		 * Defaults Section
		 * ==========
		 */
        
        add_settings_section(
            'defaults',
            __( 'Defaults', 'twinfield' ),
            array( $this, 'section_view' ),
            'twinfield-settings'
        );
        
        add_settings_field(
            'wp_twinfield_default_invoice_type',
            __( 'Default Invoice Type', 'twinfield' ),
            array( $this, 'render_text' ),
            'twinfield-settings',
            'defaults',
            array(
                'label_for' => 'wp_twinfield_default_invoice_type',
                'classes' => array( 'regular-text', 'code' )
            )
        );
        
        register_setting( 'wp_twinfield_api', 'wp_twinfield_default_invoice_type' );
        
		/**
		 * =========
		 * Permalinks Section
		 * ==========
		 */

		add_settings_section(
			'permalinks',
			__( 'Permalink', 'twinfield' ),
			array( $this, 'section_view' ),
			'twinfield-settings'
		);

		add_settings_field(
			'wp_twinfield_invoice_slug',
			__( 'Invoice Slug', 'twinfield' ),
			array( $this, 'render_text' ),
			'twinfield-settings',
			'permalinks',
			array(
				'label_for' => 'wp_twinfield_invoice_slug',
				'classes'   => array( 'regular-text', 'code' )
			)
		);

		add_settings_field(
			'wp_twinfield_customer_slug',
			__( 'Customer Slug', 'twinfield' ),
			array( $this, 'render_text' ),
			'twinfield-settings',
			'permalinks',
			array(
				'label_for' => 'wp_twinfield_customer_slug',
				'classes'   => array( 'regular-text', 'code' )
			)
		);

		// Settings for the permalinks section
		register_setting( 'wp_twinfield_api', 'wp_twinfield_invoice_slug' );
		register_setting( 'wp_twinfield_api', 'wp_twinfield_customer_slug' );
	}

	public function section_view() {}

	public function render_text( $attributes ) {
		$attributes = wp_parse_args( $attributes, array(
 			'id'      => '',
 			'type'    => 'text',
 			'name'    => '',
 			'value'   => '',
			'classes' => array( 'regular-text' )
		) );
		
		printf(
			'<input id="%s" name="%s" value="%s" type="%s" class="%s" />',
			esc_attr( $attributes['label_for'] ),
			esc_attr( $attributes['label_for'] ),
			esc_attr( get_option( $attributes['label_for'] ) ),
			esc_attr( $attributes['type'] ),
			esc_attr( implode( ' ', $attributes['classes'] ) )
		);
		
		if ( isset( $attributes['description'] ) ) {
			printf(
				'<span class="description">%s</span>',
				$attributes['description']
			);
		}
	}

	public function render_password( $attributes ) {
		$attributes['type'] = 'password';

		$this->render_text( $attributes );
	}

	public static function tab_html( $section, $default = false ) {

		printf(
			"<a class='nav-tab %s' href='%s'>%s</a>",
			( self::is_active_tab( $section ) || ( ! filter_has_var( INPUT_GET, 'tab' ) && 'api' === $section ) ) ? 'nav-tab-active' : '',
			add_query_arg( array( 'page' => 'twinfield-settings', 'tab' => $section ), admin_url( 'admin.php' ) ),
			ucfirst( $section )
		);

	}

	public static function is_active_tab( $section ) {
		return ( $section === filter_input( INPUT_GET, 'tab', FILTER_SANITIZE_STRING ) );
	}

	public static function active_tab_group() {
		$tab = self::active_tab();

		return 'wp_twinfield_' . $tab ;
	}

	public static function active_tab() {
		return ( $tab = filter_input( INPUT_GET, 'tab', FILTER_SANITIZE_STRING ) ) ? $tab : 'api';
	}
}
