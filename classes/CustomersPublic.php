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

		/**
		 * Constructs and initializes an customers public object.
		 */
	public function __construct( Plugin $plugin ) {
		$this->plugin = $plugin;

		// Actions.
		add_action( 'init', array( $this, 'init' ) );

		add_action( 'query_vars', array( $this, 'query_vars' ) );

		add_action( 'parse_query', array( $this, 'parse_query' ) );

		add_action( 'template_redirect', array( $this, 'template_redirect' ) );

		// Filters.
		add_filter( 'wp_title_parts', array( $this, 'wp_title_parts' ) );
	}

	public function init() {
		$prefix = $this->plugin->get_url_prefix();

		// Rewrite Rules
		// @see https://make.wordpress.org/core/2015/10/07/add_rewrite_rule-accepts-an-array-of-query-vars-in-wordpress-4-4/
		$slug = get_option( 'twinfield_customer_slug', _x( 'customer', 'Customer slug for frontend', 'twinfield' ) );

		add_rewrite_rule(
			$prefix . '/' . $slug . '/([^/]+)/?',
			array(
				'twinfield'           => true,
				'twinfield_debtor_id' => '$matches[1]',
			),
			'top'
		);
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
	 * Parse query.
	 *
	 * @see https://github.com/WordPress/WordPress/blob/4.6.1/wp-includes/query.php#L1872-L1879
	 */
	public function parse_query( $query ) {
		$query->is_twinfield_customer = null !== $query->get( 'twinfield_debtor_id', null );

		if ( $query->is_twinfield_customer ) {
			$query->is_home = false;
		}
	}

	/**
	 * Tempalte redirect.
	 */
	public function template_redirect() {
		$customer_id = get_query_var( 'twinfield_debtor_id', null );

		if ( is_null( $customer_id ) ) {
			return;
		}

		if ( ! is_user_logged_in() ) {
			wp_redirect( wp_login_url( site_url( get_option( 'twinfield_customer_slug', _x( 'customer', 'Customer slug for frontend', 'twinfield' ) ) . '/' . $customer_id ) ) );
		}

		if ( empty( $customer_id ) || ! current_user_can( 'twinfield_read_customer' ) ) {
			include get_404_template();

			exit;
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
