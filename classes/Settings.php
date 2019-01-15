<?php

namespace Pronamic\WP\Twinfield\Plugin;

class Settings {
	/**
	 * Twinfield plugin object.
	 *
	 * @var Plugin
	 */
	private $plugin;

		/**
		 * Constructs and initialize Twinfield plugin settings.
		 */
	public function __construct( Plugin $plugin ) {
		$this->plugin = $plugin;

		add_action( 'admin_init', array( $this, 'admin_init' ) );
	}

		/**
		 * Admin initialize
		 */
	public function admin_init() {
		// Section - General.
		add_settings_section(
			'twinfield_general',
			__( 'General', 'twinfield' ),
			'__return_false',
			'twinfield'
		);

		// Client ID.
		register_setting( 'twinfield', 'twinfield_authorization_method' );

		add_settings_field(
			'twinfield_authorization_method',
			__( 'Authorization Method', 'twinfield' ),
			__NAMESPACE__ . '\SettingFields::radio_buttons',
			'twinfield',
			'twinfield_general',
			array(
				'label_for' => 'twinfield_authorization_method',
				'options'   => array(
					''               => __( 'None', 'twinfield' ),
					'openid_connect' => __( 'OpenID Connect Authentication', 'twinfield' ),
					'web_services'   => __( 'Web Services Authentication', 'twinfield' ),
				),
			)
		);

		if ( 'openid_connect' === get_option( 'twinfield_authorization_method' ) ) {
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
				__NAMESPACE__ . '\SettingFields::render_text',
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
				__NAMESPACE__ . '\SettingFields::render_text',
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
				__NAMESPACE__ . '\SettingFields::render_text',
				'twinfield',
				'twinfield_openid_connect_authentication',
				array(
					'label_for' => 'twinfield_openid_connect_redirect_uri',
					'type'      => 'url',
				)
			);

			// Connect Link.
			if ( $this->plugin->openid_connect_provider ) {
				add_settings_field(
					'twinfield_openid_connect_link',
					__( 'Connection', 'twinfield' ),
					array( $this, 'field_connect_link' ),
					'twinfield',
					'twinfield_openid_connect_authentication'
				);
			}
		}

		if ( 'web_services' === get_option( 'twinfield_authorization_method' ) ) {
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
				__NAMESPACE__ . '\SettingFields::render_text',
				'twinfield',
				'twinfield_web_services_authentication',
				array( 'label_for' => 'twinfield_username' )
			);

			add_settings_field(
				'twinfield_password',
				__( 'Password', 'twinfield' ),
				__NAMESPACE__ . '\SettingFields::render_password',
				'twinfield',
				'twinfield_web_services_authentication',
				array( 'label_for' => 'twinfield_password' )
			);

			add_settings_field(
				'twinfield_organisation',
				__( 'Organisation', 'twinfield' ),
				__NAMESPACE__ . '\SettingFields::render_text',
				'twinfield',
				'twinfield_web_services_authentication',
				array( 'label_for' => 'twinfield_organisation' )
			);

			// Registered settings for the api section
			register_setting( 'twinfield', 'twinfield_username' );
			register_setting( 'twinfield', 'twinfield_password' );
			register_setting( 'twinfield', 'twinfield_organisation' );
		}

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
			'twinfield_default_cluster',
			/* translators: use same translations as on Twinfield.com. */
			_x( 'Cluster', 'twinfield.com', 'twinfield' ),
			__NAMESPACE__ . '\SettingFields::render_text',
			'twinfield',
			'twinfield_defaults',
			array(
				'label_for' => 'twinfield_default_cluster',
			)
		);

		add_settings_field(
			'twinfield_default_office_code',
			/* translators: use same translations as on Twinfield.com. */
			_x( 'Company Code', 'twinfield.com', 'twinfield' ),
			__NAMESPACE__ . '\SettingFields::render_text',
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
			__NAMESPACE__ . '\SettingFields::render_text',
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
			'twinfield_default_vat_code',
			__( 'VAT Code', 'twinfield' ),
			__NAMESPACE__ . '\SettingFields::render_text',
			'twinfield',
			'twinfield_defaults',
			array(
				'label_for'   => 'twinfield_default_vat_code',
				'classes'     => array( 'regular-text', 'code' ),
				/* translators: use same translations as on Twinfield.com. */
				'description' =>
					__( 'This VAT code is used by default to create Twinfield invoice lines unless you have specified an Twinfield VAT code on a lower level.', 'twinfield' ) . '<br />' .
					_x( 'You can find your VAT codes in Twinfield under "General » Company » VAT".', 'twinfield.com', 'twinfield' ),
			)
		);

		add_settings_field(
			'twinfield_default_article_code',
			__( 'Article Code', 'twinfield' ),
			__NAMESPACE__ . '\SettingFields::render_text',
			'twinfield',
			'twinfield_defaults',
			array(
				'label_for'   => 'twinfield_default_article_code',
				'classes'     => array( 'regular-text', 'code' ),
				/* translators: use same translations as on Twinfield.com. */
				'description' =>
					__( 'This article code is used by default to create Twinfield invoice lines unless you have specified an Twinfield article code on a lower level.', 'twinfield' ) . '<br />' .
					_x( 'You can find your articles in Twinfield under "Credit management » Items".', 'twinfield.com', 'twinfield' ),
			)
		);

		add_settings_field(
			'twinfield_default_subarticle_code',
			__( 'Subarticle Code', 'twinfield' ),
			__NAMESPACE__ . '\SettingFields::render_text',
			'twinfield',
			'twinfield_defaults',
			array(
				'label_for'   => 'twinfield_default_subarticle_code',
				'classes'     => array( 'regular-text', 'code' ),
				/* translators: use same translations as on Twinfield.com. */
				'description' =>
					__( 'This subarticle code is used by default to create Twinfield invoice lines unless you have specified an Twinfield subarticle code on a lower level.', 'twinfield' ) . '<br />' .
					_x( 'You can find your articles in Twinfield under "Credit management » Items".', 'twinfield.com', 'twinfield' ),
			)
		);

		register_setting( 'twinfield', 'twinfield_default_cluster' );
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
		if ( empty( $this->plugin->openid_connect_provider ) ) {
			return;
		}

		$openid_connect_provider = $this->plugin->openid_connect_provider;

		$access_token = $this->plugin->get_access_token();

		$label = __( 'Connect with Twinfield', 'twinfield' );

		if ( $access_token ) {
			$label = __( 'Reconnect with Twinfield', 'twinfield' );
		}

		$state               = new \stdClass();
		$state->redirect_uri = add_query_arg( 'page', 'twinfield_settings', admin_url( 'admin.php' ) );

		$url = $openid_connect_provider->get_authorize_url( $state );

		printf(
			'<a href="%s">%s</a>',
			esc_url( $url ),
			esc_html( $label )
		);
	}

	/**
	 * Section.
	 */
	public function section_openid_connect_authentication() {
		printf(
			'Go to the %s in order to register your OpenID Connect / OAuht 2.0 client and get your Client Id (and optional client secret).',
			sprintf(
				'<a href="%s" target="_blank">%s</a>',
				esc_url( 'https://www.twinfield.nl/openid-connect-request/' ),
				esc_html__( 'Twinfield web site', 'twinfield' )
			)
		);
	}

	/**
	 * Section.
	 */
	public function section_web_services_authentication() {
		echo __( 'Session login is deprecated and will be removed. End of life date will be announced later.', 'twinfield' ), '<br />';
		echo __( 'For web services authentication use Open ID Connect instead.', 'twinfield' );
	}

	/**
	 * Section permalinks
	 */
	public function section_permalinks( $args ) {
		include plugin_dir_path( $this->plugin->file ) . 'admin/section-permalinks.php';
	}

	/**
	 * Render input for permalink setting.
	 *
	 * @param array $args
	 */
	public function input_permalink( $args ) {
		$url = home_url( $this->plugin->get_url_prefix() . '/' );

		echo '<code>', esc_html( $url ), '</code> ';

		SettingFields::render_text( $args );
	}
}
