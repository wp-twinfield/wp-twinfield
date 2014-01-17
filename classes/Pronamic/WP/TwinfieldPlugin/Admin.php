<?php

class Pronamic_WP_TwinfieldPlugin_Admin {
	/**
	 * Instance of this class.
	 *
	 * @since 1.0.0
	 *
	 * @var self
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

		// Actions
		add_action( 'admin_init', array( $this, 'admin_init' ) );

		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
	}

	//////////////////////////////////////////////////

	/**
	 * Admin initialize
	 */
	public function admin_init() {
		$this->settings = Pronamic_WP_TwinfieldPlugin_Settings::get_instance( $this->plugin );

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
			array( $this, 'page_twinfield' ),
			$this->plugin->plugins_url( 'assets/admin/images/icon-16x16.png' )
		);

		add_submenu_page(
			'twinfield',
			_x( 'Twinfield Companies', 'twinfield.com', 'twinfield' ),
			_x( 'Companies', 'twinfield.com', 'twinfield' ),
			'twinfield_read_offices',
			'twinfield_offices',
			array( $this, 'page_offices' )
		);

		add_submenu_page(
			'twinfield',
			__( 'Twinfield Customers', 'twinfield' ),
			__( 'Customers', 'twinfield' ),
			'twinfield_read_customer',
			'twinfield_customers',
			array( $this, 'page_customers' )
		);

		add_submenu_page(
			'twinfield',
			__( 'Twinfield Invoices', 'twinfield' ),
			__( 'Invoices', 'twinfield' ),
			'twinfield_read_invoice',
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
			__( 'Twinfield Form Builder', 'twinfield' ),
			__( 'Form Builder', 'twinfield' ),
			'twinfield_form_builder',
			'twinfield_form_builder',
			array( $this, 'page_form_builder' )
		);

		add_submenu_page(
			'twinfield',
			__( 'Merger Tool', 'twinfield' ),
			__( 'Merger Tool', 'twinfield' ),
			'twinfield_merger_tool',
			'twinfield_merger_tool',
			array( $this, 'page_merger_tool' )
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

		// Scripts
		wp_register_script(
			'twinfield_sync',
			$this->plugin->plugins_url( 'assets/admin/js/WP_Twinfield_Sync.js' ),
			array( 'jquery' ),
			'1.0.0'
		);

		wp_register_script(
			'WP_Twinfield',
			$this->plugin->plugins_url( 'assets/admin/js/WP_Twinfield.js' ),
			array( 'jquery' ),
			'1.0.0'
		);

		wp_register_script(
			'FormBuilderUI',
			$this->plugin->plugins_url( 'assets/admin/js/FormBuilderUI.js' ),
			array( 'jquery'),
			'1.0.0'
		);
	
		wp_localize_script( 'WP_Twinfield', 'WP_Twinfield_Vars', array(
			'spinner' => admin_url( 'images/wpspin_light.gif' )
		) );
	
		// Auto enqueued assets
		wp_enqueue_style( 'twinfield-admin' );
		wp_enqueue_script( 'WP_Twinfield' );
	}

	//////////////////////////////////////////////////
	
	/**
	 * Page
	 * 
	 * @param string $id
	 */
	public function page( $id ) {
		$filename = 'views/page-' . $id . '.php';
		
		$this->plugin->display( $filename );
	}

	// Helper functions
	public function page_twinfield() { $this->page( 'twinfield' ); }
	public function page_offices() { $this->page( 'offices' ); }
	public function page_customers() { $this->page( 'customers' ); }
	public function page_invoices() { $this->page( 'invoices' ); }
	public function page_settings() { $this->page( 'settings' ); }
	public function page_form_builder() { $this->page( 'form_builder' ); }
	public function page_merger_tool() { $this->page( 'merger_tool' ); }
	public function page_documentation() { $this->page( 'documentation' ); }

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
