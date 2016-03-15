<?php

namespace Pronamic\WP\Twinfield\Plugin;

use Pronamic\WP\Twinfield\Customers\CustomerService;
use Pronamic\WP\Twinfield\Offices\OfficeService;
use Pronamic\WP\Twinfield\SalesInvoices\SalesInvoiceService;

class Admin {
	/**
	 * Twinfield plugin object.
	 *
	 * @var Plugin
	 */
	private $plugin;

	//////////////////////////////////////////////////

	/**
	 * Constructs and initialize Twinfield plugin admin
	 */
	public function __construct( Plugin $plugin ) {
		$this->plugin = $plugin;

		// Actions
		add_action( 'admin_init', array( $this, 'admin_init' ) );

		add_action( 'admin_menu', array( $this, 'admin_menu' ) );

		// Settings
		$this->settings = new Settings( $plugin );

		// Other
		$this->customers_admin = new CustomersAdmin( $plugin );
		$this->articles_admin  = new ArticlesAdmin( $plugin );
		$this->invoices_admin  = new InvoicesAdmin( $plugin );
	}

	//////////////////////////////////////////////////

	/**
	 * Admin initialize
	 */
	public function admin_init() {
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
	}

	//////////////////////////////////////////////////

	/**
	 * Admin menu
	 */
	public function admin_menu() {
		add_menu_page(
			__( 'Twinfield', 'twinfield' ),
			__( 'Twinfield', 'twinfield' ),
			'manage_options',
			'twinfield',
			array( $this, 'page_dashboard' ),
			$this->plugin->plugins_url( 'assets/admin/images/icon-16x16.png' )
		);

		add_submenu_page(
			'twinfield',
			_x( 'Twinfield Companies', 'twinfield.com', 'twinfield' ),
			_x( 'Companies', 'twinfield.com', 'twinfield' ),
			'manage_options',
			'twinfield_offices',
			array( $this, 'page_offices' )
		);

		add_submenu_page(
			'twinfield',
			__( 'Twinfield Customers', 'twinfield' ),
			__( 'Customers', 'twinfield' ),
			'manage_options',
			'twinfield_customers',
			array( $this, 'page_customers' )
		);

		add_submenu_page(
			'twinfield',
			__( 'Twinfield Invoices', 'twinfield' ),
			__( 'Invoices', 'twinfield' ),
			'manage_options',
			'twinfield_invoices',
			array( $this, 'page_invoices' )
		);

		add_submenu_page(
			'twinfield',
			__( 'Twinfield Settings', 'twinfield' ),
			__( 'Settings', 'twinfield' ),
			'manage_options',
			'twinfield_settings',
			array( $this, 'page_settings' )
		);

		add_submenu_page(
			'twinfield',
			__( 'Twinfield Documentation', 'twinfield' ),
			__( 'Documentation', 'twinfield' ),
			'manage_options',
			'twinfield_documentation',
			array( $this, 'page_documentation' )
		);
	}

	//////////////////////////////////////////////////

	/**
	 * Admin enqueue scripts
	 */
	public function admin_enqueue_scripts() {
		// Styles
		wp_register_style(
			'twinfield-admin',
			$this->plugin->plugins_url( 'assets/admin/css/twinfield_admin.css' ),
			array(),
			'1.0.0'
		);

		// Auto enqueued assets
		wp_enqueue_style( 'twinfield-admin' );
	}

	//////////////////////////////////////////////////

	/**
	 * Page dashboard.
	 */
	public function page_dashboard() {
		include plugin_dir_path( $this->plugin->file ) . 'admin/page-dashboard.php';
	}

	/**
	 * Page offices.
	 */
	public function page_offices() {
		$xml_processor = $this->plugin->get_xml_processor();

		$service = new OfficeService( $xml_processor );

		$offices = $service->get_offices();

		include plugin_dir_path( $this->plugin->file ) . 'admin/page-offices.php';
	}

	/**
	 * Page customers.
	 */
	public function page_customers() {
		$twinfield_response = null;

		if ( filter_has_var( INPUT_GET, 'twinfield_customer_id' ) ) {
			$xml_processor = $this->plugin->get_xml_processor();

			$service = new CustomerService( $xml_processor );

			$office = get_option( 'twinfield_default_office_code' );

			$twinfield_response = $service->get_customer( $office, filter_input( INPUT_GET, 'twinfield_customer_id', FILTER_SANITIZE_STRING ) );
		}

		include plugin_dir_path( $this->plugin->file ) . 'admin/page-customers.php';
	}

	/**
	 * Page invoices.
	 */
	public function page_invoices() {
		$twinfield_response = null;

		if ( filter_has_var( INPUT_GET, 'twinfield_invoice_id' ) ) {
			$xml_processor = $this->plugin->get_xml_processor();

			$service = new SalesInvoiceService( $xml_processor );

			$office = get_option( 'twinfield_default_office_code' );
			$type   = get_option( 'twinfield_default_invoice_type', 'FACTUUR' );

			$twinfield_response = $service->get_sales_invoice( $office, $type, filter_input( INPUT_GET, 'twinfield_invoice_id', FILTER_SANITIZE_STRING ) );
		}

		include plugin_dir_path( $this->plugin->file ) . 'admin/page-invoices.php';
	}

	/**
	 * Page settings.
	 */
	public function page_settings() {
		include plugin_dir_path( $this->plugin->file ) . 'admin/page-settings.php';
	}

	/**
	 * Page documentation.
	 */
	public function page_documentation() {
		include plugin_dir_path( $this->plugin->file ) . 'admin/page-documentation.php';
	}
}
