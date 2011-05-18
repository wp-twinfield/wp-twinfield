<?php
/*
Plugin Name: Twinfield
Plugin URI: http://pronamic.eu/wordpress/twinfield/
Description: This plugin makes a connection with the Twinfield adminsitration software.
Version: 0.1
Requires at least: 3.0
Author: Pronamic
Author URI: http://pronamic.eu/
License: GPL
*/

class Twinfield {
	public static function bootstrap() {
		// add_action('init', array(__CLASS__, 'initialize'));

		add_action('admin_init', array(__CLASS__, 'adminInitialize'));

		add_action('admin_menu', array(__CLASS__, 'adminMenu'));

		add_action('template_redirect', array(__CLASS__, 'templateRedirect'));

		add_filter('generate_rewrite_rules', array(__CLASS__, 'generateRewriteRules'));

		add_filter('query_vars', array(__CLASS__, 'queryVars'));

		add_filter('wp_loaded',array(__CLASS__, 'flushRules'));
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
			// Determine template
			$templates = array();
			$templates[] = 'twinfield-' . $id . '.php';
			$templates[] = 'twinfield.php';

			$template = locate_template($templates);

			if(!$template) {
				$template = __DIR__ . '/templates/test.php';
			}

			if(is_file($template)) {
				include $template;

    			exit;
			}
		}
	}

	public static function adminInitialize() {
		// Settings
		register_setting('twinfield', 'twinfield-username');
		register_setting('twinfield', 'twinfield-password');
		register_setting('twinfield', 'twinfield-wsdl');

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
		include 'page-twinfield.php';
	}

	public static function pageSettings() {
		include 'page-settings.php';
	}
}

Twinfield::bootstrap();
