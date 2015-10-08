<?php

namespace Pronamic\WP\Twinfield\Plugin;

use Pronamic\WP\Twinfield\Credentials;
use Pronamic\WP\Twinfield\Client;
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

	/**
	 * Get XML processor
	 */
	public function get_xml_processor() {
		$user         = get_option( 'twinfield_username' );
		$password     = get_option( 'twinfield_password' );
		$organisation = get_option( 'twinfield_organisation' );

		$credentials = new Credentials( $user, $password, $organisation );

		$client = new Client();

		$logon_response = $client->logon( $credentials );

		$session = $client->get_session( $logon_response );

		$xml_processor = new XMLProcessor( $session );

		return $xml_processor;
	}
}
