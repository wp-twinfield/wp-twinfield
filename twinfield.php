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
			//include 'vendor/autoload.php';
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
		public function autoload( $className ) {
			$className = ltrim($className, '\\');
			$fileName  = '';
			$namespace = '';
			if ($lastNsPos = strrpos($className, '\\')) {
				$namespace = substr($className, 0, $lastNsPos);
				$className = substr($className, $lastNsPos + 1);
				$fileName  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
			}
			$fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';

			if ( file_exists( dirname( __FILE__ ) . '/twinfield/src/' . $fileName ) ) {
				require dirname( __FILE__ ) . '/twinfield/src/' . $fileName;
			} elseif ( file_exists( dirname( __FILE__ ) . '/includes/' . $fileName ) ) {
				require dirname( __FILE__ ) . '/includes/' . $fileName;
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
		
		public function admin_init() {
			// Load the settings
			$this->settings = new \Pronamic\WP\Twinfield\Settings\Settings();
			
			$this->settings->register_settings();
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
				plugins_url( 'assets/admin/images/icon-16x16.png', PRONAMIC_TWINFIELD_FILE )
			);
            
            add_submenu_page(
                'twinfield',
                __( 'Customer Query', 'twinfield' ),
                __( 'Customer', 'twinfield' ),
                'twinfield-query-customer',
                'twinfield-query-customer',
                array( $this, 'page_query_customer' )
            );
            
            add_submenu_page(
                'twinfield',
                __( 'Invoice Query', 'twinfield' ),
                __( 'Invoice', 'twinfield' ),
                'twinfield-query-invoice',
                'twinfield-query-invoice',
                array( $this, 'page_query_invoice' )
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
				__( 'Twinfield Form Builder', 'twinfield' ), 
				__( 'Form Builder', 'twinfield' ), 
				'twinfield-form-builder', 
				'twinfield-form-builder', 
				array( $this, 'page_form_builder' )
			);

			add_submenu_page(
				'twinfield', 
				__( 'Merger Tool', 'twinfield' ), 
				__( 'Merger Tool', 'twinfield' ), 
				'twinfield-merger', 
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

        public function page_query_customer() {
            $view = new View( PRONAMIC_TWINFIELD_FOLDER . '/views/Pronamic/WP/Customer' );
            
            if ( filter_has_var( INPUT_GET, 'twinfield_customer_id' ) ) {
                global $twinfield_config;
                
                $customer_factory = new \Pronamic\Twinfield\Customer\CustomerFactory( $twinfield_config );
                
                $customer = $customer_factory->get(
                    filter_input( INPUT_GET, 'twinfield_customer_id', FILTER_VALIDATE_INT )
                );
                
                if ( ! $customer_factory->getResponse()->isSuccessful() )
                    $view->setVariable( 'error_messages', $customer_factory->getResponse()->getErrorMessages() );
                
            } else {
                $customer = false;
            }
            
            $view
                ->setView( 'render_customer_admin' )
                ->setVariable( 'customer', $customer )
                ->render();
        }
        
        public function page_query_invoice() {
            $view = new View( PRONAMIC_TWINFIELD_FOLDER . '/views/Pronamic/WP/Invoice' );
            
            if ( filter_has_var( INPUT_GET, 'twinfield_invoice_id' ) ) {
                global $twinfield_config;
                
                $invoice_factory = new \Pronamic\Twinfield\Invoice\InvoiceFactory( $twinfield_config );
                
                $invoice = $invoice_factory->get(
                    'FACTUUR',
                    filter_input( INPUT_GET, 'twinfield_invoice_id', FILTER_VALIDATE_INT )
                );
                
                if ( ! $invoice_factory->getResponse()->isSuccessful() )
                    $view->setVariable( 'error_messages', $invoice_factory->getResponse()->getErrorMessages() );
            } else {
                $invoice = false;
            }
            
            $view
                ->setView( 'render_invoice_admin' )
                ->setVariable( 'invoice', $invoice )
                ->render();
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