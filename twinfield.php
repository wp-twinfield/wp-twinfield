<?php
/*
Plugin Name: Twinfield
Plugin URI: http://wp.pronamic.eu/plugins/twinfield/
Description: A base plugin to make the connection with the Twinfield administration software.

Author: Pronamic
Author URI: http://www.pronamic.eu/

Version: 1.0.1
Requires at least: 3.0

Text Domain: twinfield
Domain Path: /languages/

License: GPL

GitHub URI: https://github.com/pronamic/wp-twinfield
*/

define( 'PRONAMIC_TWINFIELD_FILE', __FILE__ );
define( 'PRONAMIC_TWINFIELD_FOLDER', plugin_dir_path( PRONAMIC_TWINFIELD_FILE ) );

use Pronamic\WP\Twinfield\FormBuilder as Form;

if ( ! class_exists( 'Twinfield' ) ) :

	/**
	 * Twinfield Class
	 *
	 * Base Plugin class that bootstraps the rest of the plugin. Loads modules, sets autoloader
	 * loads the required files, registers forms, makes and handles menu items and their callbacks.
	 *
	 * @todo move into own file
	 *
	 * @package Twinfield
	 *
	 * @author Leon Rowland <leon@rowland.nl>
	 * @author Remco Tolsma <remcotolsma@pronamic.nl>
	 *
	 * @version 1.0.0
	 */
	class Twinfield {

		/**
		 * Holds Merge component
		 * @var \Pronamic\WP\Twinfield\Merge\Merge
		 */
		public $merge;

		/**
		 * Holds the Invoice component
		 * @var \Pronamic\WP\Twinfield\Invoice\Invoice
		 */
		public $invoice;

		/**
		 * Holds the Customer component
		 * @var \Pronamic\WP\Twinfield\Customer\Customer
		 */
		public $customer;

		/**
		 * Holds the article component
		 * @var \Pronamic\WP\Twinfield\Article\Article
		 */
		public $article;

		/**
		 * Holds the FormBuilder component
		 * @var \Pronamic\WP\Twinfield\FormBuilder\FormBuilder
		 */
		public $form_builder;

		public function __construct() {
			add_action( 'init', array( $this, 'init' ) );

			add_action( 'wp_twinfield_formbuilder_load_forms', array( $this, 'load_forms' ) );

			spl_autoload_register( array( $this, 'autoload' ) );

			add_action( 'plugins_loaded', array( $this, 'plugins_loaded' ), 9 );
		}

		public function plugins_loaded() {
			do_action( 'twinfield_bootstrap' );
		}

		/**
		 * Autoloads classes. When I start removing the composer package this method will probably be
		 * replaced with a PSR standard autoloader
		 *
		 * @access public
		 * @param string $name
		 * @return void
		 */
		public function autoload( $className ) {
			$className = ltrim( $className, '\\' );
			$fileName  = '';
			$namespace = '';
			if ( $lastNsPos = strrpos( $className, '\\' ) ) {
				$namespace = substr( $className, 0, $lastNsPos );
				$className = substr( $className, $lastNsPos + 1 );
				$fileName  = str_replace( '\\', DIRECTORY_SEPARATOR, $namespace ) . DIRECTORY_SEPARATOR;
			}
			$fileName .= str_replace( '_', DIRECTORY_SEPARATOR, $className ) . '.php';

			if ( file_exists( dirname( __FILE__ ) . '/twinfield/src/' . $fileName ) ) {
				require dirname( __FILE__ ) . '/twinfield/src/' . $fileName;
			} elseif ( file_exists( dirname( __FILE__ ) . '/classes/' . $fileName ) ) {
				require dirname( __FILE__ ) . '/classes/' . $fileName;
			}
		}

		/**
		 * Loads the text domain and registers the global $twinfield_config
		 * with the required credentials
		 *
		 * Loads the required components for every load ( often these components
		 * have hooks of their own that need to be regsitered )
		 *
		 * @global Pronamic\Twinfield\Secure\Config $twinfield_config
		 *
		 * @todo abstract the options into a settings class
		 *
		 * @hooked init
		 *
		 * @access public
		 * @return void
		 */
		public function init() {
			global $twinfield_config;
			$twinfield_config = new Pronamic\Twinfield\Secure\Config();

			$twinfield_config->setCredentials(
				get_option( 'twinfield_username' ),
				get_option( 'twinfield_password' ),
				get_option( 'twinfield_organisation' ),
				get_option( 'twinfield_default_office_code' )
			);

			// Load the modules
			$this->merge		 = new \Pronamic\WP\Twinfield\Merge\Merge();
			$this->invoice		 = new \Pronamic\WP\Twinfield\Invoice\Invoice();
			$this->customer		 = new \Pronamic\WP\Twinfield\Customer\Customer();
			$this->article		 = new \Pronamic\WP\Twinfield\Article\Article();

			// Load the FormBuilder Component
			$this->form_builder  = new Form\FormBuilder();
		}

		/**
		 * Registers forms in the Factory to be used in menu generation and general submission
		 * processes.
		 *
		 * Registers the following forms:
		 *
		 * - customer
		 * - invoice
		 *
		 * @hooked wp_twinfield_formbuilder_load_forms
		 *
		 * @access public
		 * @return void
		 */
		public function load_forms() {
			// Get the default forms
			$customer_form = new Form\Form\Customer();
			$customer_form->set_view( PRONAMIC_TWINFIELD_FOLDER . '/views/Pronamic/WP/FormBuilder/create_form_customer.php' );

			$invoice_form = new Form\Form\Invoice();
			$invoice_form->set_view( PRONAMIC_TWINFIELD_FOLDER . '/views/Pronamic/WP/FormBuilder/create_form_invoice.php' );

			// Register them
			Form\FormBuilderFactory::register_form( 'customer', $customer_form );
			Form\FormBuilderFactory::register_form( 'invoice', $invoice_form );
		}
	}

endif;

global $twinfield;
$twinfield = new Twinfield();

global $twinfield_plugin;

$twinfield_plugin = Pronamic_WP_TwinfieldPlugin_Plugin::get_instance( __FILE__ );
