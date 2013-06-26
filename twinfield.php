<?php

/**
 * Plugin Name: Twinfield
 * Plugin URI: http://pronamic.nl/wordpress/twinfield/
 * Description: A base plugin to make the connection with the Twinfield administration software
 * 
 * Author: Pronamic
 * Author URI: http://pronamic.nl
 * 
 * Version: 0.1
 * Requires at least: 3.0
 * 
 * Text Domain: twinfield
 * Domain Path: /languages/
 * 
 * License: GPL
 */

define( 'PRONAMIC_TWINFIELD_FILE', __FILE__ );
define( 'PRONAMIC_TWINFIELD_FOLDER', dirname( PRONAMIC_TWINFIELD_FILE ) );

use ZFramework\Base\View;
use Pronamic\WP\Twinfield\FormBuilder as Form;

if ( ! class_exists( 'Twinfield' ) ) :

	/**
	 * Twinfield Class
	 * 
	 * Base Plugin class that bootstraps the rest of the plugin. Loads modules, sets autoloader
	 * loads the required files, registers forms, makes and handles menu items and their callbacks.
	 * 
	 * @package Twinfield
	 * 
	 * @author Leon Rowland <leon@rowland.nl>
	 * @author Remco Tolsma <remcotolsma@pronamic.nl>
	 * 
	 * @version 1.0.0
	 * 
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

			add_action( 'admin_init', array( $this, 'admin_init' ) );
			add_action( 'admin_menu', array( $this, 'admin_menu' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
			add_action( 'wp_twinfield_formbuilder_load_forms', array( $this, 'load_forms' ) );

			$this->includes();

			spl_autoload_register( array( $this, 'autoload' ) );
		}

		/**
		 * Base includes for every load.
		 * 
		 * @todo minimize/remove
		 * 
		 * @access public
		 * @return void
		 */
		public function includes() {
			include 'vendor/autoload.php';
			include 'twinfield-functions.php';
		}

		
		/**
		 * Autoloads classes. When I start removing the composer package this method will probably be
		 * replaced with a PSR standard autoloader
		 * 
		 * @access public
		 * @param string $name
		 * @return void
		 */
		public function autoload( $name ) {
			$name = str_replace( '\\', DIRECTORY_SEPARATOR, $name );

			$file = dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . $name . '.php';

			if ( is_file( $file ) ) {
				require_once $file;
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
			load_plugin_textdomain( 'twinfield', false, dirname( plugin_basename( PRONAMIC_TWINFIELD_FILE ) ) . '/languages/' );
			
			global $twinfield_config;
			$twinfield_config = new Pronamic\Twinfield\Secure\Config();

			$twinfield_config->setCredentials(
				get_option( 'twinfield_username' ), 
				get_option( 'twinfield_password' ), 
				get_option( 'twinfield_organisation' ), 
				get_option( 'twinfield_office_code' )
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
		 * Registers the default settings for WP Twinfield.
		 * 
		 * @todo abstract into settings class
		 * 
		 * @hooked admin_init
		 * 
		 * @access public
		 * @return void
		 */
		public function admin_init() {
			register_setting( 'twinfield', 'twinfield_username' );
			register_setting( 'twinfield', 'twinfield_password' );
			register_setting( 'twinfield', 'twinfield_organisation' );
			register_setting( 'twinfield', 'twinfield_office_code' );
		}

		/**
		 * Adds all menu items required for WP Twinfield
		 * 
		 * @hooked admin_menu
		 * 
		 * @access public
		 * @return void
		 */
		public function admin_menu() {
			// Top level menu item
			add_menu_page(
				__( 'Twinfield', 'twinfield' ), 
				__( 'Twinfield', 'twinfield' ), 
				'manage_options', 
				'twinfield', 
				array( $this, 'page_parent' ), 
				plugins_url( 'images/icon-16x16.png', PRONAMIC_TWINFIELD_FILE )
			);

			// Sub pages
			add_submenu_page(
				'twinfield', 
				__( 'Twinfield Settings', 'twinfield' ), 
				__( 'Settings', 'twinfield' ), 
				'manage_options', 
				'twinfield-settings', 
				array( $this, 'page_settings' )
			);

			add_submenu_page(
				'twinfield', 
				__( 'Twinfield Offices', 'twinfield' ), 
				__( 'Offices', 'twinfield' ), 
				'manage_options', 
				'twinfield-offices', 
				array( $this, 'page_offices' )
			);

			add_submenu_page(
				'twinfield', 
				__( 'Twinfield Form Builder', 'twinfield' ), 
				__( 'Form Builder', 'twinfield' ), 
				'manage_options', 
				'twinfield-form-builder', 
				array( $this, 'page_form_builder' )
			);

			add_submenu_page(
				'twinfield', 
				__( 'Merger Tool', 'twinfield' ), 
				__( 'Merger Tool', 'twinfield' ), 
				'manage_options', 
				'twinfield-merger', 
				array( $this, 'page_merge' )
			);

			add_submenu_page(
				'twinfield', 
				__( 'Twinfield Documentation', 'twinfield' ), 
				__( 'Documentation', 'twinfield' ), 
				'manage_options', 
				'twinfield-documentation', 
				array( $this, 'page_documentation' )
			);
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

		/**
		 * Registers all scripts (js/css) to be used by Twinfield.  Autoloads the
		 * twinfield-admin css file.
		 * 
		 * @asset js FormBuilderUI
		 * 
		 * @access public
		 * @return void
		 */
		public function admin_scripts() {
			// Styles for admin
			wp_register_style( 
				'twinfield-admin', 
				plugins_url( 'assets/admin/css/twinfield_admin.css', PRONAMIC_TWINFIELD_FILE ) 
			);
			
			// Javascripts for admin
			wp_register_script( 
				'FormBuilderUI', 
				plugins_url( 'assets/admin/js/FormBuilderUI.js', PRONAMIC_TWINFIELD_FILE ), 
				array( 'jquery') 
			);
			
			// Auto enqueued assets
			wp_enqueue_style( 'twinfield-admin' );
		}

		/**
		 * Callback to display the parent menu item page
		 * 
		 * @access public
		 * @return void
		 */
		public function page_parent() {
			$view = new View( PRONAMIC_TWINFIELD_FOLDER . '/views/Twinfield' );
			$view->setView( 'page_parent' )->render();
		}

		/**
		 * Callback to display the settings page.
		 * 
		 * @access public
		 * @return void
		 */
		public function page_settings() {
			$view = new View( PRONAMIC_TWINFIELD_FOLDER . '/views/Twinfield' );
			$view->setView( 'page_settings' )->render();
		}

		/**
		 * Callback to display the offices page.
		 * 
		 * @access public
		 * @return void
		 */
		public function page_offices() {
			$view = new View( PRONAMIC_TWINFIELD_FOLDER . '/views/Twinfield' );
			$view->setView( 'page_offices' )->render();
		}

		/**
		 * Callback to display the form builder page.
		 * 
		 * @calls action | wp_twinfield_formbuilder_load_forms
		 * @enqueues script | FormBuilderUI
		 * 
		 * @access public
		 * @return void
		 */
		public function page_form_builder() {
			do_action( 'wp_twinfield_formbuilder_load_forms' );
			
			// Load FormBuilderUI JS Script on this page
			wp_enqueue_script( 'FormBuilderUI' );
			
			$view = new View( PRONAMIC_TWINFIELD_FOLDER . '/views/Twinfield' );
			$view->setView( 'page_form_builder' )->render();
		}

		/**
		 * Callback to display the merge page.
		 * 
		 * @access public
		 * @return void
		 */
		public function page_merge() {
			$view = new View( PRONAMIC_TWINFIELD_FOLDER . '/views/Twinfield' );
			$view->setView( 'page_merge' )->render();
		}

		/**
		 * Callback to display the documentation page.
		 * 
		 * @access public
		 * @return void
		 */
		public function page_documentation() {
			$view = new View( PRONAMIC_TWINFIELD_FOLDER . '/views/Twinfield' );
			$view->setView( 'page_documentation' )->render();
		}

	}

endif;
	
global $twinfield;
$twinfield = new Twinfield();