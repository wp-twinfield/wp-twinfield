<?php

namespace Pronamic\WP\Twinfield\Plugin;

/**
 * Admin scripts.
 */
class AdminScripts {
	/**
	 * Construct.
	 */
	public function __construct( $plugin ) {
		$this->plugin = $plugin;
	}

	/**
	 * Setup.
	 */
	public function setup() {
		add_action( 'admin_enqueue_scripts', array( $this, 'register' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ) );
	}

	/**
	 * Register.
	 */
	public function register() {
		$uri = get_template_directory_uri();

		$min = SCRIPT_DEBUG ? '' : '.min';

		// Select2 - https://select2.org/.
		wp_register_script(
			'select2',
			'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.1/js/select2' . $min . '.js',
			array(
				'jquery',
			),
			'4.0.6-rc.1',
			true
		);

		$locale = get_locale();

		$language = strtok( $locale, '_' );

		wp_register_script(
			'select2-i18n',
			'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.1/js/i18n/' . $language . '.js',
			array(
				'select2',
			),
			'4.0.6-rc.1',
			true
		);

		wp_register_style(
			'select2',
			'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.1/css/select2' . $min . '.css',
			array(),
			'4.0.6-rc.1'
		);

		// Plugin.
		wp_register_script(
			'twinfield',
			$this->plugin->plugins_url( 'assets/admin/js/admin.js' ),
			array(
				'jquery',
				'select2',
			),
			'1.0.0',
			true
		);

		wp_localize_script(
			'twinfield',
			'twinfield',
			array(
				'ajax' => (object) array(
					'offices' => rest_url( 'twinfield/v1/customers' ),
					'customers' => rest_url( 'twinfield/v1/customers' ),
				),
			)
		);
	}

	/**
	 * Enqueue.
	 */
	public function enqueue() {
		// Select2.
		wp_enqueue_script( 'select2' );
		wp_enqueue_script( 'select2-i18n' );

		wp_enqueue_style( 'select2' );

		// Plugin.
		wp_enqueue_script( 'twinfield' );
	}
}
