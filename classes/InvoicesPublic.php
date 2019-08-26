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

		/**
		 * Constructs and initializes an invoices public object.
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
		$slug = get_option( 'twinfield_invoice_slug', _x( 'invoice', 'Invoice slug for front end', 'twinfield' ) );

		add_rewrite_rule(
			$prefix . '/' . $slug . '/([^/]+)/html-pdf/?',
			array(
				'twinfield'                  => true,
				'twinfield_sales_invoice_id' => '$matches[1]',
				'twinfield_view'             => 'html-pdf',
			),
			'top'
		);

		add_rewrite_rule(
			$prefix . '/' . $slug . '/([^/]+)/pdf/?',
			array(
				'twinfield'                  => true,
				'twinfield_sales_invoice_id' => '$matches[1]',
				'twinfield_view'             => 'pdf',
			),
			'top'
		);

		add_rewrite_rule(
			$prefix . '/' . $slug . '/([^/]+)/?',
			array(
				'twinfield'                  => true,
				'twinfield_sales_invoice_id' => '$matches[1]',
				'twinfield_view'             => 'html',
			),
			'top'
		);

		add_rewrite_rule(
			$prefix . '/' . $slug . '/([^/]+)/([^/]+)/?',
			array(
				'twinfield'                    => true,
				'twinfield_sales_invoice_type' => '$matches[1]',
				'twinfield_sales_invoice_id'   => '$matches[2]',
				'twinfield_view'             => 'html',
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
		$query_vars[] = 'twinfield_sales_invoice_id';
		$query_vars[] = 'twinfield_sales_invoice_type';
		$query_vars[] = 'twinfield_view';

		return $query_vars;
	}

	/**
	 * Parse query.
	 *
	 * @see https://github.com/WordPress/WordPress/blob/4.6.1/wp-includes/query.php#L1872-L1879
	 */
	public function parse_query( $query ) {
		$query->is_twinfield_sales_invoice = null !== $query->get( 'twinfield_sales_invoice_id', null );

		if ( $query->is_twinfield_sales_invoice ) {
			$query->is_home = false;
		}
	}

	/**
	 * Tempalte redirect.
	 */
	public function template_redirect() {
		$id   = get_query_var( 'twinfield_sales_invoice_id', null );
		$type = get_query_var( 'twinfield_sales_invoice_type', get_option( 'wp_twinfield_default_invoice_type', 'FACTUUR' ) );
		$view = get_query_var( 'twinfield_view', null );

		if ( is_null( $id ) ) {
			return;
		}

		if ( ! is_user_logged_in() ) {
			wp_safe_redirect( wp_login_url( site_url( get_option( 'twinfield_invoice_slug', _x( 'invoice', 'Invoice slug for front end', 'twinfield' ) ) . '/' . $id ) ) );
		}

		if ( empty( $id ) || empty( $type ) || ! current_user_can( 'twinfield_read_invoice' ) ) {
			include get_404_template();

			exit;
		}

		$xml_processor = $this->plugin->get_xml_processor();

		$service = new SalesInvoiceService( $xml_processor );

		$office = get_option( 'twinfield_default_office_code' );

		$twinfield_response = $service->get_sales_invoice( $office, $type, $id );

		if ( $twinfield_response ) {
			if ( $twinfield_response->is_successful() ) {
				global $twinfield_sales_invoice;

				$twinfield_sales_invoice = $twinfield_response->get_sales_invoice();

				switch ( $view ) {
					case 'pdf':
						ob_start();

						include plugin_dir_path( $this->plugin->file ) . 'templates/sales-invoice-pdf-html.php';
						
						$html = ob_get_clean();

						$mpdf = new \Mpdf\Mpdf();
						$mpdf->WriteHTML( $html );
						$mpdf->Output();

						exit;
					case 'html-pdf':
						include plugin_dir_path( $this->plugin->file ) . 'templates/sales-invoice-pdf-html.php';

						break;
					case 'html':
					default:
						include plugin_dir_path( $this->plugin->file ) . 'templates/sales-invoice.php';

						break;
				}
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
			$title_array[] = sprintf(
				/* translators: %s: Invoice number */
				__( 'Invoice %s', 'twinfield' ),
				esc_html( $twinfield_sales_invoice->get_header()->get_number() )
			);
		}

		return $title_array;
	}
}
