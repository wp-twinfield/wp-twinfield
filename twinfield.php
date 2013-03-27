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

include 'vendor/autoload.php';

if ( function_exists( 'spl_autoload_register' ) ) {
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

		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'scripts' ) );


	}

	public static function scripts() {
		wp_register_style( 'pronamic_twinfield', dirname( __FILE__ ) . '/css/admin.css' );
		wp_enqueue_style( 'pronamic_twinfield' );

	}

	public static function init() {
		// Text domain
		$rel_path = dirname( plugin_basename( self::$file ) ) . '/languages/';

		load_plugin_textdomain( 'twinfield', false, $rel_path );

		global $twinfield_config;

		$twinfield_config = new Pronamic\Twinfield\Secure\Config();

		// Set the config class
		$twinfield_config->setCredentials(
			get_option( 'twinfield_username' ),
			get_option( 'twinfield_password' ),
			get_option( 'twinfield_organisation' ),
			get_option( 'twinfield_office_code' )
		);

		$login = new \Pronamic\Twinfield\Secure\Login( $twinfield_config );
		$login->process();

		// Modules
		new \Pronamic\WP\FormBuilder\FormBuilder();
		new \Pronamic\WP\Invoice\Invoice();
		new \Pronamic\WP\Customer\Customer();
		new \Pronamic\WP\Article\Article();

	}

	public static function flushRules() {
		global $wp_rewrite;

		$wp_rewrite->flush_rules();
	}

	public static function generateRewriteRules($wpRewrite) {
		$rules = array();


		$rules['debiteuren/([^/]+)$'] = 'index.php?twinfield_debtor_id=' . $wpRewrite->preg_index(1);

		$wpRewrite->rules = $rules + $wpRewrite->rules;
	}

	public static function queryVars($queryVars) {

		$queryVars[] = 'twinfield_debtor_id';

		return $queryVars;
	}

	public static function templateRedirect() {


		$id = get_query_var('twinfield_debtor_id');

		if(!empty($id)) {
			global $twinfield_debtor;

			$username = get_option( 'twinfield_username' );
			$password = get_option( 'twinfield_password' );
			$organisation = get_option( 'twinfield_organisation' );
			$office_code = get_option( 'twinfield_office_code' );

			$twinfield_client = new Pronamic\Twinfield\TwinfieldClient();
			$result = $twinfield_client->logon($username, $password, $organisation);

			$twinfield_debtor = $twinfield_client->read_debtor( $office_code, $id );

			// Determine template
			$templates = array();
			$templates[] = 'twinfield-debtor-' . $id . '.php';
			$templates[] = 'twinfield-debtor.php';

			$template = locate_template($templates);

			if(!$template) {
				$template = __DIR__ . '/templates/debtor.php';
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
			__( 'Twinfield Settings', 'twinfield' ) , // $page_title
			__( 'Settings', 'twinfield' ) , // $menu_title
			'manage_options' , // $capability
			'twinfield-settings' , // $menu_slug
			array( __CLASS__, 'page_settings' ) // $function
		);

		add_submenu_page(
			'twinfield' , // $parent_slug
			__( 'Twinfield Offices', 'twinfield' ) , // $page_title
			__( 'Offices', 'twinfield' ) , // $menu_title
			'manage_options' , // $capability
			'twinfield-offices' , // $menu_slug
			array( __CLASS__, 'page_offices' ) // $function
		);

		add_submenu_page(
			'twinfield' , // $parent_slug
			__( 'Twinfield Form Builder', 'twinfield' ) , // $page_title
			__( 'Form Builder', 'twinfield' ) , // $menu_title
			'manage_options' , // $capability
			'twinfield_form_builder' , // $menu_slug
			array( __CLASS__, 'form_builder' ) // $function
		);

		add_submenu_page(
			'twinfield' , // $parent_slug
			__( 'Twinfield Documentation', 'twinfield' ) , // $page_title
			__( 'Documentation', 'twinfield' ) , // $menu_title
			'manage_options' , // $capability
			'twinfield-documentation' , // $menu_slug
			array( __CLASS__, 'page_documentation' ) // $function
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

	public static function page_documentation() {
		include 'admin/documentation.php';
	}

	public static function form_builder() {
		include 'admin/form_builder.php';
	}
}

Twinfield::bootstrap( __FILE__ );
include 'twinfield-functions.php';
