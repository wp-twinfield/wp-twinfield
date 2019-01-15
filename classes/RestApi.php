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
	 *
	 * @see https://developer.wordpress.org/rest-api/extending-the-rest-api/adding-custom-endpoints/
	 */
	public function rest_api_init() {
		$namespace = 'twinfield/v1';

		register_rest_route(
			$namespace,
			'/offices',
			array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'rest_api_offices' ),
				'permission_callback' => function () {
					return true;
				},
			)
		);

		register_rest_route(
			$namespace,
			'/customers/list',
			array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'rest_api_customers_list' ),
				'permission_callback' => function () {
					return true;
				},
			)
		);

		register_rest_route(
			$namespace,
			'/customers',
			array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'rest_api_customers' ),
				'permission_callback' => function () {
					return true;
				},
				'args'                => array(
					'page'     => array(
						'description'       => 'Current page of the collection.',
						'type'              => 'integer',
						'default'           => 1,
						'sanitize_callback' => 'absint',
					),
					'per_page' => array(
						'description'       => 'Maximum number of items to be returned in result set.',
						'type'              => 'integer',
						'default'           => 10,
						'sanitize_callback' => 'absint',
					),
					'search'   => array(
						'description'       => 'Limit results to those matching a string.',
						'type'              => 'string',
						'sanitize_callback' => 'sanitize_text_field',
					),
				),
			)
		);

		register_rest_route(
			$namespace,
			'/articles',
			array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'rest_api_articles' ),
				'permission_callback' => function () {
					return true;
				},
				'args'                => array(
					'page'     => array(
						'description'       => 'Current page of the collection.',
						'type'              => 'integer',
						'default'           => 1,
						'sanitize_callback' => 'absint',
					),
					'per_page' => array(
						'description'       => 'Maximum number of items to be returned in result set.',
						'type'              => 'integer',
						'default'           => 10,
						'sanitize_callback' => 'absint',
					),
					'search'   => array(
						'description'       => 'Limit results to those matching a string.',
						'type'              => 'string',
						'sanitize_callback' => 'sanitize_text_field',
					),
				),
			)
		);
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
			$option       = new \stdClass();
			$option->code = $office->get_code();
			$option->name = $office->get_name();

			$options[] = $option;
		}

		return $options;
	}

	public function rest_api_customers_list( WP_REST_Request $request ) {
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

	public function rest_api_customers( WP_REST_Request $request ) {
		$pattern = $request->get_param( 'search' );
		$pattern = empty( $pattern ) ? '*' : '*' . $pattern . '*';

		$page     = $request->get_param( 'page' );
		$per_page = $request->get_param( 'per_page' );

		$first_row = ( ( $page - 1 ) * $per_page ) + 1;
		$max_rows  = $per_page;

		$client = $this->plugin->get_client();

		$customers_finder = new \Pronamic\WP\Twinfield\Customers\CustomerFinder( $client->get_finder() );

		$customers = $customers_finder->get_customers( $pattern, 0, $first_row, $max_rows );

		if ( ! is_array( $customers ) ) {
			return array();
		}

		if ( empty( $customers ) ) {
			return array();
		}

		$options = array();

		foreach ( $customers as $customer ) {
			$option       = new \stdClass();
			$option->code = $customer->get_code();
			$option->name = $customer->get_name();

			$options[] = $option;
		}

		return $options;
	}

	public function rest_api_articles( WP_REST_Request $request ) {
		$pattern = $request->get_param( 'search' );
		$pattern = empty( $pattern ) ? '*' : '*' . $pattern . '*';

		$page     = $request->get_param( 'page' );
		$per_page = $request->get_param( 'per_page' );

		$first_row = ( ( $page - 1 ) * $per_page ) + 1;
		$max_rows  = $per_page;

		$client = $this->plugin->get_client();

		$articles_finder = new \Pronamic\WP\Twinfield\Articles\ArticlesFinder( $client->get_finder() );

		$articles = $articles_finder->get_articles( $pattern, 0, $first_row, $max_rows );

		if ( ! is_array( $articles ) ) {
			return array();
		}

		if ( empty( $articles ) ) {
			return array();
		}

		$options = array();

		foreach ( $articles as $article ) {
			$option       = new \stdClass();
			$option->code = $article->get_code();
			$option->name = $article->get_name();

			$options[] = $option;
		}

		return $options;
	}
}
