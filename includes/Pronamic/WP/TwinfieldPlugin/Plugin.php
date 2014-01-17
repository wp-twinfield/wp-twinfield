<?php

class Pronamic_WP_TwinfieldPlugin_Plugin {
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
	private function __construct( $file ) {
		$this->file     = $file;
		$this->dir_path = plugin_dir_path( $file );

		add_action( 'init', array( $this, 'init' ) );
		
		add_action( 'plugins_loaded', array( $this, 'plugins_loaded' ) );
		
		// Admin
		if ( is_admin() ) {
			Pronamic_WP_TwinfieldPlugin_Admin::get_instance( $this );
		}
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
		load_plugin_textdomain( 'twinfield', false, dirname( plugin_basename( $this->file ) ) . '/languages/' );
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
	 * Display/iinclude the specified file
	 * 
	 * @param string $file
	 */
	public function display( $file, array $args = array() ) {
		extract( $args );

		include $this->dir_path . $file; 
	}

	//////////////////////////////////////////////////

	/**
	 * Return an instance of this class.
	 *
	 * @since 1.0.0
	 *
	 * @return object A single instance of this class.
	 */
	public static function get_instance( $file = false ) {
		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self( $file );
		}
	
		return self::$instance;
	}
}
