<?php

namespace Pronamic\WP\Twinfield\Plugin;

use WP_REST_Request;

class RestApi {
	/**
	 * Constructs and initialize Twinfield REST API object.
	 */
	public function __construct( $plugin ) {
		$this->plugin = $plugin;

		// Actions
		add_action( 'rest_api_init', array( $this, 'rest_api_init' ) );
	}

	/**
	 * REST API initialize.
	 */
	public function rest_api_init() {
		$namespace = 'twinfield/v1';

		register_rest_route( $namespace, '/offices', array(
			'methods' => 'GET',
			'callback' => array( $this, 'rest_api_offices' ),
		) );

		register_rest_route( $namespace, '/customers', array(
			'methods' => 'GET',
			'callback' => array( $this, 'rest_api_customers' ),
		) );
	}

	public function rest_api_offices( WP_REST_Request $request ) {
		$client = $this->plugin->get_client();

		$xml_processor = $client->get_xml_processor();

		$office_service = new \Pronamic\WP\Twinfield\Offices\OfficeService( $xml_processor );

		$offices = $office_service->get_offices();

		if ( ! is_array( $offices ) ) {
			return array();
		}

		if ( empty( $offices ) ) {
			return array();
		}

		$options = array();

		foreach ( $offices as $office ) {
			$option = new \stdClass();
			$option->code = $office->get_code();
			$option->name = $office->get_name();

			$options[] = $option;
		}

		return $options;
	}

	public function rest_api_customers( WP_REST_Request $request ) {
		$client = $this->plugin->get_client();

		$xml_processor = $client->get_xml_processor();

		$customer_service = new \Pronamic\WP\Twinfield\Customers\CustomerService( $xml_processor );

		$customers = $customer_service->get_customers( get_option( 'twinfield_default_office_code' ) );

		if ( ! is_array( $customers ) ) {
			return array();
		}

		if ( empty( $customers ) ) {
			return array();
		}

		return $customers;
	}
}
