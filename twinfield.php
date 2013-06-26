<?php

/*
  Plugin Name: Twinfield
  Plugin URI: http://pronamic.eu/wordpress/twinfield/
  Description: This plugin makes a connection with the Twinfield administration software.

  Version: 0.1
  Requires at least: 3.0

  Author: Pronamic
  Author URI: http://pronamic.eu/

  Text Domain: twinfield
  Domain Path: /languages/

  License: GPL
 */

define( 'PRONAMIC_TWINFIELD_FILE', __FILE__ );
define( 'PRONAMIC_TWINFIELD_FOLDER', dirname( PRONAMIC_TWINFIELD_FILE ) );

use ZFramework\Base\View;

use Pronamic\WP\Twinfield\FormBuilder as Form;

if ( ! class_exists( 'Twinfield' ) ) :

	class Twinfield {

		public $form_builder;
		public $merge;
		public $invoice;
		public $customer;
		public $article;

		public function __construct() {
			add_action( 'init', array( $this, 'init' ) );

			add_action( 'admin_init', array( $this, 'admin_init' ) );
			add_action( 'admin_menu', array( $this, 'admin_menu' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
			add_action( 'wp_twinfield_formbuilder_load_forms', array( $this, 'load_forms' ) );

			$this->includes();

			spl_autoload_register( array( $this, 'autoload' ) );
		}

		public function includes() {
			include 'vendor/autoload.php';
			include 'twinfield-functions.php';
		}

		public function autoload( $name ) {
			$name = str_replace( '\\', DIRECTORY_SEPARATOR, $name );

			$file = dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . $name . '.php';

			if ( is_file( $file ) ) {
				require_once $file;
			}
		}

		public function init() {
			global $twinfield_config;

			load_plugin_textdomain( 'twinfield', false, dirname( plugin_basename( PRONAMIC_TWINFIELD_FILE ) ) . '/languages/' );

			$twinfield_config = new Pronamic\Twinfield\Secure\Config();

			$twinfield_config->setCredentials(
					get_option( 'twinfield_username' ), get_option( 'twinfield_password' ), get_option( 'twinfield_organisation' ), get_option( 'twinfield_office_code' )
			);

			// Load the modules
			$this->merge		 = new \Pronamic\WP\Twinfield\Merge\Merge();
			$this->invoice		 = new \Pronamic\WP\Twinfield\Invoice\Invoice();
			$this->customer		 = new \Pronamic\WP\Twinfield\Customer\Customer();
			$this->article		 = new \Pronamic\WP\Twinfield\Article\Article();
			$this->form_builder  = new Form\FormBuilder();			
		}

		public function admin_init() {
			register_setting( 'twinfield', 'twinfield_username' );
			register_setting( 'twinfield', 'twinfield_password' );
			register_setting( 'twinfield', 'twinfield_organisation' );
			register_setting( 'twinfield', 'twinfield_office_code' );
		}

		public function admin_menu() {
			// Top level menu item
			add_menu_page(
				__( 'Twinfield', 'twinfield' ), __( 'Twinfield', 'twinfield' ), 'manage_options', 'twinfield', array( $this, 'page_parent' ), plugins_url( 'images/icon-16x16.png', PRONAMIC_TWINFIELD_FILE )
			);

			// Sub pages
			add_submenu_page(
				'twinfield', __( 'Twinfield Settings', 'twinfield' ), __( 'Settings', 'twinfield' ), 'manage_options', 'twinfield-settings', array( $this, 'page_settings' )
			);

			add_submenu_page(
				'twinfield', __( 'Twinfield Offices', 'twinfield' ), __( 'Offices', 'twinfield' ), 'manage_options', 'twinfield-offices', array( $this, 'page_offices' )
			);

			add_submenu_page(
				'twinfield', __( 'Twinfield Form Builder', 'twinfield' ), __( 'Form Builder', 'twinfield' ), 'manage_options', 'twinfield-form-builder', array( $this, 'page_form_builder' )
			);

			add_submenu_page(
				'twinfield', __( 'Merger Tool', 'twinfield' ), __( 'Merger Tool', 'twinfield' ), 'manage_options', 'twinfield-merger', array( $this, 'page_merge' )
			);

			add_submenu_page(
				'twinfield', __( 'Twinfield Documentation', 'twinfield' ), __( 'Documentation', 'twinfield' ), 'manage_options', 'twinfield-documentation', array( $this, 'page_documentation' )
			);
		}
		
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

		public function admin_scripts() {
			wp_register_style( 'twinfield-admin', plugins_url( 'css/twinfield_admin.css', PRONAMIC_TWINFIELD_FILE ) );
			wp_enqueue_style( 'twinfield-admin' );
		}

		public function page_parent() {
			$view = new View( PRONAMIC_TWINFIELD_FOLDER . '/views/Twinfield' );
			$view->setView( 'page_parent' )->render();
		}

		public function page_settings() {
			$view = new View( PRONAMIC_TWINFIELD_FOLDER . '/views/Twinfield' );
			$view->setView( 'page_settings' )->render();
		}

		public function page_offices() {
			$view = new View( PRONAMIC_TWINFIELD_FOLDER . '/views/Twinfield' );
			$view->setView( 'page_offices' )->render();
		}

		public function page_form_builder() {
			do_action( 'wp_twinfield_formbuilder_load_forms' );
			
			$view = new View( PRONAMIC_TWINFIELD_FOLDER . '/views/Twinfield' );
			$view->setView( 'page_form_builder' )->render();
		}

		public function page_merge() {
			$view = new View( PRONAMIC_TWINFIELD_FOLDER . '/views/Twinfield' );
			$view->setView( 'page_merge' )->render();
		}

		public function page_documentation() {
			$view = new View( PRONAMIC_TWINFIELD_FOLDER . '/views/Twinfield' );
			$view->setView( 'page_documentation' )->render();
		}

	}

endif;
	
global $twinfield;
$twinfield = new Twinfield();