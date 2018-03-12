<?php

namespace Pronamic\WP\Twinfield\Plugin;

class Settings {
	/**
	 * Twinfield plugin object.
	 *
	 * @var Plugin
	 */
	private $plugin;

	//////////////////////////////////////////////////

	/**
	 * Constructs and initialize Twinfield plugin settings.
	 */
	public function __construct( Plugin $plugin ) {
		$this->plugin = $plugin;

		add_action( 'admin_init', array( $this, 'admin_init' ) );
	}

	//////////////////////////////////////////////////

	/**
	 * Admin initialize
	 */
	public function admin_init() {
		// Section - OpenID Connect Authentication.
		add_settings_section(
			'twinfield_openid_connect_authentication',
			__( 'OpenID Connect Authentication', 'twinfield' ),
			array( $this, 'section_openid_connect_authentication' ),
			'twinfield'
		);

		// Client ID.
		register_setting( 'twinfield', 'twinfield_openid_connect_client_id' );

		add_settings_field(
			'twinfield_openid_connect_client_id',
			__( 'Client ID', 'twinfield' ),
			array( $this, 'render_text' ),
			'twinfield',
			'twinfield_openid_connect_authentication',
			array(
				'label_for' => 'twinfield_openid_connect_client_id',
			)
		);

		// Client Secret.
		register_setting( 'twinfield', 'twinfield_openid_connect_client_secret' );

		add_settings_field(
			'twinfield_openid_connect_client_secret',
			__( 'Client Secret', 'twinfield' ),
			array( $this, 'render_text' ),
			'twinfield',
			'twinfield_openid_connect_authentication',
			array(
				'label_for' => 'twinfield_openid_connect_client_secret',
				'type'      => 'password',
			)
		);

		// Redirect URI.
		register_setting( 'twinfield', 'twinfield_openid_connect_redirect_uri' );

		add_settings_field(
			'twinfield_openid_connect_redirect_uri',
			__( 'Redirect URI', 'twinfield' ),
			array( $this, 'render_text' ),
			'twinfield',
			'twinfield_openid_connect_authentication',
			array(
				'label_for' => 'twinfield_openid_connect_redirect_uri',
				'type'      => 'url',
			)
		);

		// Connect Link.
		$client_id     = get_option( 'twinfield_openid_connect_client_id' );
		$client_secret = get_option( 'twinfield_openid_connect_client_secret' );
		$redirect_uri  = get_option( 'twinfield_openid_connect_redirect_uri' );

		if ( $client_id && $client_secret && $redirect_uri ) {
			add_settings_field(
				'twinfield_openid_connect_link',
				__( 'Connection', 'twinfield' ),
				array( $this, 'field_connect_link' ),
				'twinfield',
				'twinfield_openid_connect_authentication'
			);
		}

		// Section - Web Services Authentication.
		add_settings_section(
			'twinfield_web_services_authentication',
			__( 'Web Services Authentication', 'twinfield' ),
			array( $this, 'section_web_services_authentication' ),
			'twinfield'
		);

		add_settings_field(
			'twinfield_username',
			__( 'Username', 'twinfield' ),
			array( $this, 'render_text' ),
			'twinfield',
			'twinfield_web_services_authentication',
			array( 'label_for' => 'twinfield_username' )
		);

		add_settings_field(
			'twinfield_password',
			__( 'Password', 'twinfield' ),
			array( $this, 'render_password' ),
			'twinfield',
			'twinfield_web_services_authentication',
			array( 'label_for' => 'twinfield_password' )
		);

		add_settings_field(
			'twinfield_organisation',
			__( 'Organisation', 'twinfield' ),
			array( $this, 'render_text' ),
			'twinfield',
			'twinfield_web_services_authentication',
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
			'__return_false',
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
				'classes'	 => array( 'regular-text', 'code' ),
				/* translators: use same translations as on Twinfield.com. */
				'description' => _x( 'You can find your invoice types in Twinfield under "Credit management » Invoicing » Invoicing types".', 'twinfield.com', 'twinfield' ),
			)
		);

		add_settings_field(
			'twinfield_default_vat_code',
			__( 'VAT Code', 'twinfield' ),
			array( $this, 'render_text' ),
			'twinfield',
			'twinfield_defaults',
			array(
				'label_for'   => 'twinfield_default_vat_code',
				'classes'	 => array( 'regular-text', 'code' ),
				/* translators: use same translations as on Twinfield.com. */
				'description' =>
					__( 'This VAT code is used by default to create Twinfield invoice lines unless you have specified an Twinfield VAT code on a lower level.', 'twinfield' ) . '<br />' .
					_x( 'You can find your VAT codes in Twinfield under "General » Company » VAT".', 'twinfield.com', 'twinfield' ),
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
				'classes'	 => array( 'regular-text', 'code' ),
				/* translators: use same translations as on Twinfield.com. */
				'description' =>
					__( 'This article code is used by default to create Twinfield invoice lines unless you have specified an Twinfield article code on a lower level.', 'twinfield' ) . '<br />' .
					_x( 'You can find your articles in Twinfield under "Credit management » Items".', 'twinfield.com', 'twinfield' ),
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
				'classes'	  => array( 'regular-text', 'code' ),
				/* translators: use same translations as on Twinfield.com. */
				'description' =>
					__( 'This subarticle code is used by default to create Twinfield invoice lines unless you have specified an Twinfield subarticle code on a lower level.', 'twinfield' ) . '<br />' .
					_x( 'You can find your articles in Twinfield under "Credit management » Items".', 'twinfield.com', 'twinfield' ),
			)
		);

		register_setting( 'twinfield', 'twinfield_default_office_code' );
		register_setting( 'twinfield', 'twinfield_default_invoice_type' );
		register_setting( 'twinfield', 'twinfield_default_vat_code' );
		register_setting( 'twinfield', 'twinfield_default_article_code' );
		register_setting( 'twinfield', 'twinfield_default_subarticle_code' );

		/*
		 * Permalinks
		 */
		add_settings_section(
			'twinfield_permalinks',
			__( 'Permalinks', 'twinfield' ),
			array( $this, 'section_permalinks' ),
			'twinfield'
		);

		add_settings_field(
			'twinfield_invoice_slug',
			__( 'Invoice Slug', 'twinfield' ),
			array( $this, 'input_permalink' ),
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
			array( $this, 'input_permalink' ),
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

	/**
	 * Section.
	 */
	public function field_connect_link() {
		$client_id     = get_option( 'twinfield_openid_connect_client_id' );
		$client_secret = get_option( 'twinfield_openid_connect_client_secret' );
		$redirect_uri  = get_option( 'twinfield_openid_connect_redirect_uri' );

		$state = new \stdClass();
		$state->redirect_uri = add_query_arg( 'page', 'twinfield_settings', admin_url( 'admin.php' ) );

		$url = 'https://login.twinfield.com/auth/authentication/connect/authorize';

		$url = add_query_arg( array(
			'client_id'     => $client_id,
			'response_type' => 'code',
			'scope'         => implode( '+', array(
				'openid',
				'twf.user',
				'twf.organisation',
				'twf.organisationUser',
				'offline_access',
			) ),
			'redirect_uri'  => $redirect_uri,
			// @see https://auth0.com/docs/protocols/oauth2/oauth-state
			'state'         => base64_encode( wp_json_encode( $state ) ),
			'nonce'         => wp_create_nonce( 'twinfield-auth' ),
		), $url );

		printf(
			'<a href="%s">%s</a>',
			esc_url( $url ),
			esc_html__( 'Connect with Twinfield', 'twinfield' )
		);

		$access_token = get_option( 'twinfield_access_token' );

		$info = $this->plugin->get_token_info( $access_token );

		var_dump( $info );
	}

	/**
	 * Section.
	 */
	public function section_openid_connect_authentication() {

	}

	/**
	 * Section.
	 */
	public function section_web_services_authentication() {
		echo __( 'Session login is deprecated and will be removed. End of life date will be announced later.', 'twinfield' ), '<br />';
		echo __( 'For web services authentication use Open ID Connect instead.', 'twinfield' );
	}

	/**
	 * Array to HTML attributes
	 *
	 * @param array $pieces
	 */
	private function array_to_html_attributes( array $attributes ) {
		$html  = '';
		$space = '';

		foreach ( $attributes as $key => $value ) {
			$html .= $space . $key . '="' . esc_attr( $value ) . '"';

			$space = ' ';
		}

		return $html;
	}

	/**
	 * Section permalinks
	 */
	public function section_permalinks( $args ) {
		include plugin_dir_path( $this->plugin->file ) . 'admin/section-permalinks.php';
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
			'classes' => array( 'regular-text' ),
		) );

		if ( isset( $attributes['label_for'] ) ) {
			$attributes['name']  = $attributes['label_for'];
			$attributes['value'] = get_option( $attributes['label_for'] );

			unset( $attributes['label_for'] );
		}

		if ( isset( $attributes['classes'] ) ) {
			$attributes['class'] = implode( ' ', $attributes['classes'] );

			unset( $attributes['classes'] );
		}

		$description = null;
		if ( isset( $attributes['description'] ) ) {
			$description = $attributes['description'];

			unset( $attributes['description'] );
		}

		printf( '<input %s />', $this->array_to_html_attributes( $attributes ) ); //xss ok

		if ( $description ) {
			printf( //xss ok
				'<span class="description"><br />%s</span>',
				$description
			); //xss ok
		}
	}

	/**
	 * Render input for permalink setting.
	 *
	 * @param array $args
	 */
	public function input_permalink( $args ) {
		$url = home_url( $this->plugin->get_url_prefix() . '/' );

		echo '<code>', esc_html( $url ), '</code> ';

		$this->render_text( $args );
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
}
