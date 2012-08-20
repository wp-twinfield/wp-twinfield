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

require_once 'twinfield/library/bootstrap.php';

class Twinfield {
	const TEXT_DOMAIN = 'twinfield';

	const SALT = 'Leap_of_faith';

	public static $file;

	public static function bootstrap($file) {
		self::$file = $file;

		add_action('init', array(__CLASS__, 'initialize'));

		add_action('admin_init', array(__CLASS__, 'adminInitialize'));

		add_action('admin_menu', array(__CLASS__, 'adminMenu'));

		add_action('template_redirect', array(__CLASS__, 'templateRedirect'));

		add_filter('generate_rewrite_rules', array(__CLASS__, 'generateRewriteRules'));

		add_filter('query_vars', array(__CLASS__, 'queryVars'));

		add_filter('wp_loaded',array(__CLASS__, 'flushRules'));
	}

	private static function encrypt($text) {
		$ivSize = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
		$iv = mcrypt_create_iv($ivSize, MCRYPT_RAND);

		$text = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, self::SALT, $text, MCRYPT_MODE_ECB, $iv);
		$text = base64_encode($text);
		$text = trim($text);

		return trim($text); 
	}

	private static function decrypt($text) {
		$ivSize = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
		$iv = mcrypt_create_iv($ivSize, MCRYPT_RAND);

		$text = base64_decode($text);
		$text = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, self::SALT, $text, MCRYPT_MODE_ECB, $iv);
		$text = trim($text);

        return $text;
	}

	public static function initialize() {
		// Load plugin text domain
		$relPath = dirname(plugin_basename(self::$file)) . '/languages/';

		load_plugin_textdomain(self::TEXT_DOMAIN, false, $relPath);
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
			
			$username = self::decrypt(get_option('twinfield-username'));
			$password = self::decrypt(get_option('twinfield-password'));
			$organisation = self::decrypt(get_option('twinfield-organisation'));

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

	public static function sanitizeEncrypted($value) {
		return self::encrypt($value);
	}

	public static function adminInitialize() {
		// Settings
		register_setting('twinfield', 'twinfield-username', array(__CLASS__, 'sanitizeEncrypted'));
		register_setting('twinfield', 'twinfield-password', array(__CLASS__, 'sanitizeEncrypted'));
		register_setting('twinfield', 'twinfield-organisation', array(__CLASS__, 'sanitizeEncrypted'));

		// Styles
		wp_enqueue_style(
			'twinfield-admin' , 
			plugins_url('css/admin.css', __FILE__)
		);
	}

	public static function adminMenu() {
		add_menu_page(
			$pageTitle = 'Twinfield' , 
			$menuTitle = 'Twinfield' , 
			$capability = 'manage_options' , 
			$menuSlug = __FILE__ , 
			$function = array(__CLASS__, 'page') , 
			$iconUrl = plugins_url('images/icon-16x16.png', __FILE__)
		);

		// @see _add_post_type_submenus()
		// @see wp-admin/menu.php
		add_submenu_page(
			$parentSlug = __FILE__ , 
			$pageTitle = 'Settings' , 
			$menuTitle = 'Settings' , 
			$capability = 'manage_options' , 
			$menuSlug = 'twinfield-settings' , 
			$function = array(__CLASS__, 'pageSettings')
		);
	}

	public static function page() {
		include 'admin/twinfield.php';
	}

	public static function pageSettings() {
		include 'admin/settings.php';
	}
}

Twinfield::bootstrap(__FILE__);
