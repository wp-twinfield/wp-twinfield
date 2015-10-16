<?php

namespace Pronamic\WP\Twinfield\Plugin;

use Pronamic\WP\Twinfield\Customers\CustomerService;

class CustomersPublic {
	/**
	 * Twinfield plugin object.
	 *
	 * @var Plugin
	 */
	private $plugin;

	//////////////////////////////////////////////////

	/**
	 * Constructs and initializes an customers public object.
	 */
	public function __construct( Plugin $plugin ) {
		$this->plugin = $plugin;

		// Actions.
		add_action( 'generate_rewrite_rules', array( $this, 'generate_rewrite_rules' ) );

		add_action( 'query_vars', array( $this, 'query_vars' ) );

		add_action( 'template_redirect', array( $this, 'template_redirect' ) );

		// Filters.
		add_filter( 'wp_title_parts', array( $this, 'wp_title_parts' ) );
	}

	/**
	 * Generate rewrite rules.
	 *
	 * @param \WP_Rewrite $wp_rewrite
	 */
	public function generate_rewrite_rules( $wp_rewrite ) {
		$rules = array();

		// Get the customer slug from options.
		$slug = get_option( 'twinfield_customer_slug', _x( 'customer', 'Customer slug for frontend', 'twinfield' ) );

		$rules[ $slug . '/([^/]+)$' ] = 'index.php?twinfield_debtor_id=' . $wp_rewrite->preg_index( 1 );

		$wp_rewrite->rules = array_merge( $rules, $wp_rewrite->rules );
	}

	/**
	 * Query vars.
	 *
	 * @param array $query_vars
	 */
	public function query_vars( $query_vars ) {
		$query_vars[] = 'twinfield_debtor_id';

		return $query_vars;
	}

	/**
	 * Tempalte redirect.
	 */
	public function template_redirect() {
		$customer_id = get_query_var( 'twinfield_debtor_id', null );

		if ( is_null( $customer_id ) ) {
			return;
		}

		if ( ! is_user_logged_in() || ! current_user_can( 'twinfield_read_customer' ) ) {
			wp_redirect( wp_login_url( site_url( get_option( 'twinfield_customer_slug', _x( 'customer', 'Customer slug for frontend', 'twinfield' ) ) . '/' . $customer_id ) ) );
		}

		$xml_processor = $this->plugin->get_xml_processor();

		$service = new CustomerService( $xml_processor );

		$office = get_option( 'twinfield_default_office_code' );

		$twinfield_response = $service->get_customer( $office, $customer_id );

		if ( $twinfield_response ) {
			if ( $twinfield_response->is_successful() ) {
				global $twinfield_customer;

				$twinfield_customer = $twinfield_response->get_customer();

				include plugin_dir_path( $this->plugin->file ) . 'templates/customer.php';
			} else {
				include get_404_template();
			}
		} else {
			include get_404_template();
		}

		exit;
	}

	/**
	 * WordPress title.
	 *
	 * @param string $title
	 */
	public function wp_title_parts( $title_array ) {
		global $twinfield_customer;

		if ( isset( $twinfield_customer ) ) {
			$title_array[] = $twinfield_customer->get_name();
		}

		return $title_array;
	}
}
