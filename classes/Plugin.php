<?php

namespace Pronamic\WP\Twinfield\Plugin;

use Pronamic\WP\Twinfield\Credentials;
use Pronamic\WP\Twinfield\Client;
use Pronamic\WP\Twinfield\Finder;
use Pronamic\WP\Twinfield\XMLProcessor;
use Pronamic\WP\Twinfield\Authentication\OpenIdConnectProvider;

class Plugin {
	/**
	 * Plugin file
	 *
	 * @var string
	 */
	public $file;

	/**
	 * Plugin dir path
	 *
	 * @var string
	 */
	public $dir_path;

	/**
	 * Constructs and initialize Pronamic WordPress Extensions plugin
	 *
	 * @param string $file
	 */
	public function __construct( $file ) {
		$this->file     = $file;
		$this->dir_path = plugin_dir_path( $file );

		// Includes
		include_once $this->dir_path . '/includes/functions.php';
		include_once $this->dir_path . '/includes/template.php';

		// Actions
		add_action( 'init', array( $this, 'init' ) );

		add_action( 'plugins_loaded', array( $this, 'plugins_loaded' ), 10, 9 );

		// Admin
		if ( is_admin() ) {
			$this->admin = new Admin( $this );
		}

		// Other
		$this->rest_api         = new RestApi( $this );
		$this->invoices_public  = new InvoicesPublic( $this );
		$this->customers_public = new CustomersPublic( $this );

		// OpenID Connect Provider
		if ( 'openid_connect' === get_option( 'twinfield_authorization_method' ) ) {
			$client_id     = get_option( 'twinfield_openid_connect_client_id' );
			$client_secret = get_option( 'twinfield_openid_connect_client_secret' );
			$redirect_uri  = get_option( 'twinfield_openid_connect_redirect_uri' );

			if ( $client_id && $client_secret && $redirect_uri ) {
				$this->openid_connect_provider = new OpenIdConnectProvider( $client_id, $client_secret, $redirect_uri );

				add_action( 'init', array( $this, 'maybe_handle_twinfield_oauth' ) );
			}
		}
	}

	/**
	 * Initialize.
	 */
	public function init() {

	}

	/**
	 * Maybe handle Twinfield OpenID Authorization.
	 */
	public function maybe_handle_twinfield_oauth() {
		if ( empty( $this->openid_connect_provider ) ) {
			return;
		}

		if ( ! filter_has_var( INPUT_GET, 'code' ) ) {
			return;
		}

		if ( ! filter_has_var( INPUT_GET, 'state' ) ) {
			return;
		}

		if ( ! filter_has_var( INPUT_GET, 'session_state' ) ) {
			return;
		}

		$code          = filter_input( INPUT_GET, 'code', FILTER_SANITIZE_STRING );
		$state         = filter_input( INPUT_GET, 'state', FILTER_SANITIZE_STRING );
		$session_state = filter_input( INPUT_GET, 'session_state', FILTER_SANITIZE_STRING );

		$data = $this->openid_connect_provider->get_access_token( $code );

		$this->set_access_token( $data );

		$url = add_query_arg( array(
			'code'          => false,
			'state'         => false,
			'session_state' => false,
		) );

		wp_redirect( $url );

		exit;
	}

	/**
	 * Get URL prefix.
	 *
	 * @see https://github.com/WP-API/api-core/blob/develop/wp-includes/rest-api/rest-functions.php#L204-L220
	 * @return string
	 */
	public function get_url_prefix() {
		return 'twinfield';
	}

	/**
	 * Plugins loaded
	 */
	public function plugins_loaded() {
		// Load text domain.
		load_plugin_textdomain( 'twinfield', false, dirname( plugin_basename( $this->file ) ) . '/languages/' );

		// Bootstrap.
		do_action( 'twinfield_bootstrap' );
	}

	/**
	 * Plugins URL
	 *
	 * @param string $path
	 */
	public function plugins_url( $path ) {
		return plugins_url( $path, $this->file );
	}

	public function get_access_token() {
		$access_token  = get_option( 'twinfield_openid_connect_access_token' );
		$refresh_token = get_option( 'twinfield_openid_connect_refresh_token' );
		$expiration    = get_option( 'twinfield_openid_connect_expiration' );

		if ( $expiration > time() ) {
			return $access_token;
		}

		if ( empty( $this->openid_connect_provider ) ) {
			return false;
		}

		if ( empty( $refresh_token ) ) {
			return false;
		}

		$data = $this->openid_connect_provider->refresh_token( $refresh_token );

		return $this->set_access_token( $data );
	}

	public function set_access_token( $data ) {
		if ( is_object( $data ) ) {
			if ( isset( $data->id_token ) ) {
				update_option( 'twinfield_openid_connect_id_token', $data->id_token );
			}

			if ( isset( $data->access_token ) ) {
				update_option( 'twinfield_openid_connect_access_token', $data->access_token );
			}

			if ( isset( $data->expires_in ) ) {
				update_option( 'twinfield_openid_connect_expires_in', $data->expires_in );
			}

			if ( isset( $data->token_type ) ) {
				update_option( 'twinfield_openid_connect_token_type', $data->token_type );	
			}

			if ( isset( $data->refresh_token ) ) {
				update_option( 'twinfield_openid_connect_refresh_token', $data->refresh_token );
			}
		}

		$access_token = get_option( 'twinfield_openid_connect_access_token' );

		$validation = $this->openid_connect_provider->get_access_token_validation( $access_token );

		if ( is_object( $validation ) ) {
			if ( isset( $validation->exp ) ) {
				update_option( 'twinfield_openid_connect_expiration', $validation->exp );
			}

			if ( isset( $validation->nbf ) ) {
				update_option( 'twinfield_openid_connect_not_before_time', $validation->nbf );
			}

			if ( isset( $validation->{'twf.clusterUrl'} ) ) {
				update_option( 'twinfield_openid_connect_cluster', $validation->{'twf.clusterUrl'} );	
			}
		}

		return $access_token;
	}

	private function get_authentication_strategy() {
		$method = get_option( 'twinfield_authorization_method' );

		switch ( $method ) {
			case 'openid_connect':
				$access_token = $this->get_access_token();
				$office       = get_option( 'twinfield_default_office_code' );
				$cluster      = get_option( 'twinfield_openid_connect_cluster' );

				$authentication_strategy = new \Pronamic\WP\Twinfield\Authentication\OpenIdConnectAuthenticationStrategy( $access_token, $office, $cluster );

				return $authentication_strategy;
			case 'web_services':
				$user         = get_option( 'twinfield_username' );
				$password     = get_option( 'twinfield_password' );
				$organisation = get_option( 'twinfield_organisation' );

				$credentials = new Credentials( $user, $password, $organisation );

				$authentication_strategy = new \Pronamic\WP\Twinfield\Authentication\WebServicesAuthenticationStrategy( $credentials );

				return $authentication_strategy;
		}
	}

	public function get_client() {
		$authentication_strategy = $this->get_authentication_strategy();

		if ( empty( $authentication_strategy ) ) {
			return false;
		}

		$client = new Client( $authentication_strategy );

		$client->login();

		return $client;
	}

	/**
	 * Get XML processor
	 */
	public function get_xml_processor() {
		return $this->get_client()->get_xml_processor();
	}

	/**
	 * Get finder
	 */
	public function get_finder() {
		return $this->get_client()->get_finder();
	}
}
