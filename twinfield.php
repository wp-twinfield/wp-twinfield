<?php
/*
Plugin Name: Twinfield
Plugin URI: http://pronamic.eu/wordpress/twinfield/
Description: This plugin makes a connection with the Twinfield adminsitration software.

Version: 0.1
Requires at least: 3.0

Author: Pronamic
Author URI: http://pronamic.eu/

Text Domain: twinfield
Domain Path: /languages/

License: GPL
*/

if( function_exists( 'spl_autoload_register' ) ) {
	function twinfield_autoload( $name ) {
		$name = str_replace( '\\', DIRECTORY_SEPARATOR, $name );

		$file = dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . $name . '.php';

		if ( is_file( $file ) ) {
			require_once $file;
		}
	}
	
	spl_autoload_register( 'twinfield_autoload' );
}


class Twinfield {
	public static $file;

	public static function bootstrap( $file ) {
		self::$file = $file;

		add_action( 'init', array( __CLASS__, 'init' ) );

		add_action('admin_init', array(__CLASS__, 'adminInitialize'));

		add_action('admin_menu', array(__CLASS__, 'adminMenu'));

		add_action('template_redirect', array(__CLASS__, 'templateRedirect'));

		add_filter('generate_rewrite_rules', array(__CLASS__, 'generateRewriteRules'));

		add_filter('query_vars', array(__CLASS__, 'queryVars'));

		add_filter('wp_loaded',array(__CLASS__, 'flushRules'));
	}

	public static function init() {
		// Text domain
		$rel_path = dirname( plugin_basename( self::$file ) ) . '/languages/';

		load_plugin_textdomain( 'twinfield', false, $rel_path );
	}

	public static function flushRules() {
		global $wp_rewrite;
	
		$wp_rewrite->flush_rules();
	}

	public static function generateRewriteRules($wpRewrite) {
		$rules = array();

		$rules['factuur/([^/]+)$'] = 'index.php?twinfield_id=' . $wpRewrite->preg_index(1);

		$wpRewrite->rules = $rules + $wpRewrite->rules;
	}

	public static function queryVars($queryVars) {
		$queryVars[] = 'twinfield_id';

		return $queryVars;
	}

	public static function templateRedirect() {
		$id = get_query_var('twinfield_id');

		if(!empty($id)) {
			global $twinfieldSalesInvoice;
			
			$username = get_option( 'twinfield_username' );
			$password = get_option( 'twinfield_password' );
			$organisation = get_option( 'twinfield_organisation' );

			$twinfieldClient = new Pronamic\Twinfield\TwinfieldClient();
			$result = $twinfieldClient->logon($username, $password, $organisation);

			$offices = $twinfieldClient->getOffices();
			foreach($offices as $office) {
				
			}

			$twinfieldSalesInvoice = $twinfieldClient->readSalesInvoice($office->getCode(), 'FACTUUR', $id);

			// Determine template
			$templates = array();
			$templates[] = 'twinfield-sales-invoice-' . $id . '.php';
			$templates[] = 'twinfield-sales-invoice.php';

			$template = locate_template($templates);

			if(!$template) {
				$template = __DIR__ . '/templates/sales-invoice.php';
			}

			if(is_file($template)) {
				include $template;

    			exit;
			}
		}
	}

	public static function adminInitialize() {
		// Settings
		register_setting( 'twinfield', 'twinfield_username' );
		register_setting( 'twinfield', 'twinfield_password' );
		register_setting( 'twinfield', 'twinfield_organisation' );
		register_setting( 'twinfield', 'twinfield_office_code' );

		// Styles
		wp_enqueue_style(
			'twinfield-admin' , 
			plugins_url('css/admin.css', __FILE__)
		);
	}

	public static function adminMenu() {
		add_menu_page(
			__( 'Twinfield', 'twinfield' ) , // $page_title
			__( 'Twinfield', 'twinfield' ) , // $menu_title
			'manage_options' , // $capability 
			'twinfield' , // $menu_slug
			array( __CLASS__, 'page' ) , // $function 
			plugins_url( 'images/icon-16x16.png', __FILE__ ) // $icon_url
		);

		add_submenu_page(
			'twinfield' , // $parent_slug
			__( 'Settings', 'twinfield' ) , // $page_title 
			__( 'Settings', 'twinfield' ) , // $menu_title
			'manage_options' , // $capability 
			'twinfield-settings' , // $menu_slug 
			array( __CLASS__, 'page_settings' ) // $function
		);

		add_submenu_page(
			'twinfield' , // $parent_slug
			__( 'Offices', 'twinfield' ) , // $page_title 
			__( 'Offices', 'twinfield' ) , // $menu_title
			'manage_options' , // $capability 
			'twinfield-offices' , // $menu_slug 
			array( __CLASS__, 'page_offices' ) // $function
		);
	}

	public static function page() {
		include 'admin/twinfield.php';
	}

	public static function page_settings() {
		include 'admin/settings.php';
	}

	public static function page_offices() {
		include 'admin/offices.php';
	}
}

Twinfield::bootstrap( __FILE__ );
