<?php

namespace Pronamic\WP\Twinfield\Plugin;

use Pronamic\WP\Twinfield\Credentials;
use Pronamic\WP\Twinfield\Client;
use Pronamic\WP\Twinfield\Finder;
use Pronamic\WP\Twinfield\XMLProcessor;

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

	//////////////////////////////////////////////////

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
		$this->invoices_public  = new InvoicesPublic( $this );
		$this->customers_public = new CustomersPublic( $this );
	}

	//////////////////////////////////////////////////

	/**
	 * Initialize
	 */
	public function init() {
		$this->maybe_handle_oauth();
	}

	public function maybe_handle_oauth() {
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

		$data = $this->get_access_token( $code );

		if ( is_object( $data ) ) {
			if ( isset( $data->id_token ) ) {
				update_option( 'twinfield_id_token', $data->id_token );
			}

			if ( isset( $data->access_token ) ) {
				update_option( 'twinfield_access_token', $data->access_token );
			}

			if ( isset( $data->expires_in ) ) {
				update_option( 'twinfield_expires_in', $data->expires_in );	
			}

			if ( isset( $data->token_type ) ) {
				update_option( 'twinfield_token_type', $data->token_type );	
			}

			if ( isset( $data->refresh_token ) ) {
				update_option( 'twinfield_refresh_token', $data->refresh_token );	
			}
		}

		$url = add_query_arg( array(
			'code'          => false,
			'state'         => false,
			'session_state' => false,
		) );

		wp_redirect( $url );

		exit;
	}

	public function get_access_token( $code ) {
		$url = 'https://login.twinfield.com/auth/authentication/connect/token';

		$client_id     = 'WordPress';
		$client_secret = 'lo5FKg3gucMaHLlhsXkn21u82XSXaO/4Tw==';
		$redirect_uri  = 'https://wordpress-twinfield.pronamic.eu/';

		$result = wp_remote_post( $url, array(
			'headers' => array(
				// @see https://developer.wordpress.org/plugins/http-api/#get-using-basic-authentication
				// @see https://c3.twinfield.com/webservices/documentation/#/ApiReference/Authentication/OpenIdConnect#General-information
				'Authorization' => 'Basic ' . base64_encode( $client_id . ':' . $client_secret ),
			),
			'body'    => array(
				'grant_type'   => 'authorization_code',
				'code'         => $code,
				'redirect_uri' => $redirect_uri,
			),
		) );

		if ( is_wp_error( $result ) ) {
			return false;
		}

		$body = wp_remote_retrieve_body( $result );

		$data = json_decode( $body );

		return $data;
	}

	public function get_token_info( $access_token ) {
		$access_token = get_option( 'twinfield_access_token' );

		if ( empty( $access_token ) ) {
			return false;
		}

		$url = 'https://login.twinfield.com/auth/authentication/connect/accesstokenvalidation';
		$url = add_query_arg( 'token', $access_token, $url );

		$result = wp_remote_get( $url );

		if ( is_wp_error( $result ) ) {
			return false;
		}

		$body = wp_remote_retrieve_body( $result );

		$data = json_decode( $body );

		return $data;
	}

	public function refresh_token() {
		$refresh_token = get_option( 'twinfield_refresh_token' );

		if ( empty( $refresh_token ) ) {
			return false;
		}

		$result = wp_remote_post( $url, array(
			'headers' => array(
				// @see https://developer.wordpress.org/plugins/http-api/#get-using-basic-authentication
				// @see https://c3.twinfield.com/webservices/documentation/#/ApiReference/Authentication/OpenIdConnect#General-information
				'Authorization' => 'Basic ' . base64_encode( $client_id . ':' . $client_secret ),
			),
			'body'    => array(
				'grant_type'    => 'refresh_token',
				'refresh_token' => $refresh_token,
			),
		) );

		if ( is_wp_error( $result ) ) {
			return false;
		}

		$body = wp_remote_retrieve_body( $result );

		$data = json_decode( $body );

		if ( is_object( $data ) ) {
			if ( isset( $data->access_token ) ) {
				update_option( 'twinfield_access_token', $data->access_token );
			}

			if ( isset( $data->expires_in ) ) {
				update_option( 'twinfield_expires_in', $data->expires_in );	
			}

			if ( isset( $data->token_type ) ) {
				update_option( 'twinfield_token_type', $data->token_type );	
			}

			if ( isset( $data->refresh_token ) ) {
				update_option( 'twinfield_refresh_token', $data->refresh_token );	
			}
		}
	}

	//////////////////////////////////////////////////

	/**
	 * Get URL prefix.
	 *
	 * @see https://github.com/WP-API/api-core/blob/develop/wp-includes/rest-api/rest-functions.php#L204-L220
	 * @return string
	 */
	public function get_url_prefix() {
		return 'twinfield';
	}

	//////////////////////////////////////////////////

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

	//////////////////////////////////////////////////

	private function get_session() {
		$user         = get_option( 'twinfield_username' );
		$password     = get_option( 'twinfield_password' );
		$organisation = get_option( 'twinfield_organisation' );

		$credentials = new Credentials( $user, $password, $organisation );

		$client = new Client();

		$logon_response = $client->logon( $credentials );

		$session = $client->get_session( $logon_response );

		return $session;
	}

	/**
	 * Get XML processor
	 */
	public function get_xml_processor() {
		$xml_processor = new XMLProcessor( $this->get_session() );

		return $xml_processor;
	}

	/**
	 * Get finder
	 */
	public function get_finder() {
		$finder = new Finder( $this->get_session() );

		return $finder;
	}
}
