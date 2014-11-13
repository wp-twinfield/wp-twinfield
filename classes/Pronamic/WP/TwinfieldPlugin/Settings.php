<?php

class Pronamic_WP_TwinfieldPlugin_Settings {
	/**
	 * Instance of this class.
	 *
	 * @since 1.0.0
	 *
	 * @var Pronamic_WP_ExtensionsPlugin_Plugin
	 */
	protected static $instance = null;

	//////////////////////////////////////////////////

	/**
	 * Extensions plugin
	 *
	 * @var Pronamic_WP_TwinfieldPlugin_Plugin
	 */
	private $plugin;

	//////////////////////////////////////////////////

	/**
	 * Constructs and initialize Twinfield plugin admin
	 */
	private function __construct( Pronamic_WP_TwinfieldPlugin_Plugin $plugin ) {
		$this->plugin = $plugin;

		/*
		 * API
		 */
		add_settings_section(
			'twinfield_login',
			__( 'Login', 'twinfield' ),
			create_function( null, "twinfield_settings_section( 'section-login' );" ),
			'twinfield'
		);

		add_settings_field(
			'twinfield_username',
			__( 'Username', 'twinfield' ),
			array( $this, 'render_text' ),
			'twinfield',
			'twinfield_login',
			array( 'label_for' => 'twinfield_username' )
		);

		add_settings_field(
			'twinfield_password',
			__( 'Password', 'twinfield' ),
			array( $this, 'render_password' ),
			'twinfield',
			'twinfield_login',
			array( 'label_for' => 'twinfield_password' )
		);

		add_settings_field(
			'twinfield_organisation',
			__( 'Organisation', 'twinfield' ),
			array( $this, 'render_text' ),
			'twinfield',
			'twinfield_login',
			array( 'label_for' => 'twinfield_organisation' )
		);

		// Registered settings for the api section
		register_setting( 'twinfield', 'twinfield_username' );
		register_setting( 'twinfield', 'twinfield_password' );
		register_setting( 'twinfield', 'twinfield_organisation' );

		/*
		 * Defaults
		 */
        add_settings_section(
            'twinfield_defaults',
            __( 'Defaults', 'twinfield' ),
            create_function( null, "twinfield_settings_section( 'section-defaults' );" ),
            'twinfield'
        );

		add_settings_field(
			'twinfield_default_office_code',
			/* translators: use same translations as on Twinfield.com. */
			_x( 'Company Code', 'twinfield.com', 'twinfield' ),
			array( $this, 'render_text' ),
			'twinfield',
			'twinfield_defaults',
			array(
				'label_for'   => 'twinfield_default_office_code',
				/* translators: use same translations as on Twinfield.com. */
				'description' => _x( 'You can find your companies in Twinfield under "Profile » Companies" or "General » System » Switch company".', 'twinfield.com', 'twinfield' ),
			)
		);

        add_settings_field(
            'twinfield_default_invoice_type',
            __( 'Invoice Type Code', 'twinfield' ),
            array( $this, 'render_text' ),
            'twinfield',
            'twinfield_defaults',
            array(
                'label_for'   => 'twinfield_default_invoice_type',
                'classes'     => array( 'regular-text', 'code' ),
				/* translators: use same translations as on Twinfield.com. */
				'description' => _x( 'You can find your invoice types in Twinfield under "Credit management » Invoicing » Invoicing types".', 'twinfield.com', 'twinfield' ),
            )
        );

        add_settings_field(
            'twinfield_default_article_code',
            __( 'Article Code', 'twinfield' ),
            array( $this, 'render_text' ),
            'twinfield',
            'twinfield_defaults',
            array(
                'label_for'   => 'twinfield_default_article_code',
                'classes'     => array( 'regular-text', 'code' ),
				/* translators: use same translations as on Twinfield.com. */
				'description' => _x( 'You can find your articles in Twinfield under "Credit management » Items".', 'twinfield.com', 'twinfield' ),
            )
        );

        add_settings_field(
            'twinfield_default_subarticle_code',
            __( 'Subarticle Code', 'twinfield' ),
            array( $this, 'render_text' ),
            'twinfield',
            'twinfield_defaults',
            array(
                'label_for'   => 'twinfield_default_subarticle_code',
                'classes'     => array( 'regular-text', 'code' ),
				/* translators: use same translations as on Twinfield.com. */
				'description' => _x( 'You can find your articles in Twinfield under "Credit management » Items".', 'twinfield.com', 'twinfield' ),
            )
        );

        register_setting( 'twinfield', 'twinfield_default_office_code' );
        register_setting( 'twinfield', 'twinfield_default_invoice_type' );
        register_setting( 'twinfield', 'twinfield_default_article_code' );
        register_setting( 'twinfield', 'twinfield_default_subarticle_code' );

		/*
		 * Permalinks
		 */
		add_settings_section(
			'twinfield_permalinks',
			__( 'Permalinks', 'twinfield' ),
			create_function( null, "twinfield_settings_section( 'section-permalinks' );" ),
			'twinfield'
		);

		add_settings_field(
			'twinfield_invoice_slug',
			__( 'Invoice Slug', 'twinfield' ),
			array( $this, 'render_text' ),
			'twinfield',
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
			'twinfield',
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

	//////////////////////////////////////////////////

	/**
	 * Array to HTML attributes
	 *
	 * @param array $pieces
	 */
	private function array_to_html_attributes( array $attributes ) {
		$html  = '';
		$space = '';

		foreach ( $attributes as $key => $value ) {
			$html .= $space . $key . '=' . '"' . esc_attr( $value ) . '"';

			$space = ' ';
		}

		return $html;
	}

	/**
	 * Render text
	 *
	 * @param array $attributes
	 */
	public function render_text( $attributes ) {
		$attributes = wp_parse_args( $attributes, array(
 			'id'      => '',
 			'type'    => 'text',
 			'name'    => '',
 			'value'   => '',
			'classes' => array( 'regular-text' )
		) );

		if ( isset( $attributes['label_for'] ) ) {
			$attributes['name']  = $attributes['label_for'];
			$attributes['value'] = get_option( $attributes['label_for'] );

			unset( $attributes['label_for'] );
		}

		if ( isset( $attributes['classes'] ) ) {
			$attributes['class'] = implode( ' ', $attributes['classes'] ) ;

			unset( $attributes['classes'] );
		}

		$description = null;
		if ( isset( $attributes['description'] ) ) {
			$description = $attributes['description'];

			unset( $attributes['description'] );
		}

		printf( '<input %s />', $this->array_to_html_attributes( $attributes ) );

		if ( $description ) {
			printf(
				'<span class="description"><br />%s</span>',
				$description
			);
		}
	}

	/**
	 * Render password
	 *
	 * @param array $attributes
	 */
	public function render_password( $attributes ) {
		$attributes['type'] = 'password';

		$this->render_text( $attributes );
	}

	//////////////////////////////////////////////////

	/**
	 * Return an instance of this class.
	 *
	 * @since 1.0.0
	 *
	 * @return object A single instance of this class.
	 */
	public static function get_instance( Pronamic_WP_TwinfieldPlugin_Plugin $plugin ) {
		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self( $plugin );
		}

		return self::$instance;
	}
}
