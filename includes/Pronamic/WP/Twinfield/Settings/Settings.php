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
			'twinfield_login',
			__( 'Login', 'twinfield' ),
			create_function( null, "twinfield_settings_section( 'section-login' );" ),
			'twinfield_settings'
		);

		add_settings_field(
			'twinfield_username',
			__( 'Username', 'twinfield' ),
			array( $this, 'render_text' ),
			'twinfield_settings',
			'twinfield_login',
			array( 'label_for' => 'twinfield_username' )
		);

		add_settings_field(
			'twinfield_password',
			__( 'Password', 'twinfield' ),
			array( $this, 'render_password' ),
			'twinfield_settings',
			'twinfield_login',
			array( 'label_for' => 'twinfield_password' )
		);

		add_settings_field(
			'twinfield_organisation',
			__( 'Organisation', 'twinfield' ),
			array( $this, 'render_text' ),
			'twinfield_settings',
			'twinfield_login',
			array( 'label_for' => 'twinfield_organisation' )
		);

		// Registered settings for the api section
		register_setting( 'twinfield', 'twinfield_username' );
		register_setting( 'twinfield', 'twinfield_password' );
		register_setting( 'twinfield', 'twinfield_organisation' );

        /**
		 * =========
		 * Defaults Section
		 * ==========
		 */
        
        add_settings_section(
            'twinfield_defaults',
            __( 'Defaults', 'twinfield' ),
            create_function( null, "twinfield_settings_section( 'section-defaults' );" ),
            'twinfield_settings'
        );

		add_settings_field(
			'twinfield_default_office_code',
			/* translators: use same translations as on Twinfield.com. */
			_x( 'Default Company', 'twinfield.com', 'twinfield' ),
			array( $this, 'render_text' ),
			'twinfield_settings',
			'twinfield_defaults',
			array(
				'label_for'   => 'twinfield_default_office_code',
				/* translators: use same translations as on Twinfield.com. */
				'description' => _x( 'You can find your companies in Twinfield under "Profile » Companies" or "General » System » Switch company".', 'twinfield.com', 'twinfield' ),
			)
		);
        
        add_settings_field(
            'twinfield_default_invoice_type',
            __( 'Default Invoice Type', 'twinfield' ),
            array( $this, 'render_text' ),
            'twinfield_settings',
            'twinfield_defaults',
            array(
                'label_for'   => 'twinfield_default_invoice_type',
                'classes'     => array( 'regular-text', 'code' ),
				/* translators: use same translations as on Twinfield.com. */
				'description' => _x( 'You can find your invoice types in Twinfield under "Credit management » Invoicing » Invoicing types".', 'twinfield.com', 'twinfield' ),
            )
        );

        register_setting( 'twinfield', 'twinfield_default_office_code' );
        register_setting( 'twinfield', 'twinfield_default_invoice_type' );
        
		/**
		 * =========
		 * Permalinks Section
		 * ==========
		 */

		add_settings_section(
			'twinfield_permalinks',
			__( 'Permalinks', 'twinfield' ),
			create_function( null, "twinfield_settings_section( 'section-permalinks' );" ),
			'twinfield_settings'
		);

		add_settings_field(
			'twinfield_invoice_slug',
			__( 'Invoice Slug', 'twinfield' ),
			array( $this, 'render_text' ),
			'twinfield_settings',
			'twinfield_permalinks',
			array(
				'label_for' => 'twinfield_invoice_slug',
				'classes'   => array( 'regular-text', 'code' ),
			)
		);

		add_settings_field(
			'twinfield_customer_slug',
			__( 'Customer Slug', 'twinfield' ),
			array( $this, 'render_text' ),
			'twinfield_settings',
			'twinfield_permalinks',
			array(
				'label_for' => 'twinfield_customer_slug',
				'classes'   => array( 'regular-text', 'code' ),
			)
		);

		// Settings for the permalinks section
		register_setting( 'twinfield', 'twinfield_invoice_slug' );
		register_setting( 'twinfield', 'twinfield_customer_slug' );
	}

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
				'<span class="description"><br />%s</span>',
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
