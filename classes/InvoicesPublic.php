<?php

namespace Pronamic\WP\Twinfield\Plugin;

use Pronamic\WP\Twinfield\SalesInvoices\SalesInvoiceService;

class InvoicesPublic {
	/**
	 * Twinfield plugin object.
	 *
	 * @var Plugin
	 */
	private $plugin;

	//////////////////////////////////////////////////

	/**
	 * Constructs and initializes an invoices public object.
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

		// Get the invoice slug from options.
		$slug = get_option( 'twinfield_invoice_slug', _x( 'invoice', 'Invoice slug for front end', 'twinfield' ) );

		$rules[ $slug . '/([^/]+)$' ] = 'index.php?twinfield_sales_invoice_id=' . $wp_rewrite->preg_index( 1 );
		$rules[ $slug . '/([^/]+)/([^/]+)$' ] = 'index.php?twinfield_sales_invoice_type=' . $wp_rewrite->preg_index( 2 ) . '&twinfield_sales_invoice_id=' . $wp_rewrite->preg_index( 1 );

		$wp_rewrite->rules = array_merge( $rules, $wp_rewrite->rules );
	}

	/**
	 * Query vars.
	 *
	 * @param array $query_vars
	 */
	public function query_vars( $query_vars ) {
		$query_vars[] = 'twinfield_sales_invoice_id';
		$query_vars[] = 'twinfield_sales_invoice_type';

		return $query_vars;
	}

	/**
	 * Tempalte redirect.
	 */
	public function template_redirect() {
		$id   = get_query_var( 'twinfield_sales_invoice_id', null );
		$type = get_query_var( 'twinfield_sales_invoice_type', get_option( 'wp_twinfield_default_invoice_type', 'FACTUUR' ) );

		if ( is_null( $id ) ) {
			return;
		}

		if ( empty( $id ) || empty( $type ) ) {
			include get_404_template();

			exit;
		}

		if ( ! is_user_logged_in() || ! current_user_can( 'twinfield_read_invoice' ) ) {
			wp_redirect( wp_login_url( site_url( get_option( 'twinfield_invoice_slug', _x( 'invoice', 'Invoice slug for front end', 'twinfield' ) ) . '/' . $invoice_id ) ) );
		}

		$xml_processor = $this->plugin->get_xml_processor();

		$service = new SalesInvoiceService( $xml_processor );

		$office = get_option( 'twinfield_default_office_code' );

		$twinfield_response = $service->get_sales_invoice( $office, $type, $id );

		if ( $twinfield_response ) {
			if ( $twinfield_response->is_successful() ) {
				global $twinfield_sales_invoice;

				$twinfield_sales_invoice = $twinfield_response->get_sales_invoice();

				include plugin_dir_path( $this->plugin->file ) . 'templates/sales-invoice.php';
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
		global $twinfield_sales_invoice;

		if ( isset( $twinfield_sales_invoice ) ) {
			$title_array[] = sprintf( __( 'Invoice %s', 'twinfield' ), esc_html( $twinfield_sales_invoice->get_header()->get_number() ) );
		}

		return $title_array;
	}
}
