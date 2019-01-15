<?php

namespace Pronamic\WP\Twinfield\Plugin;

class InvoicesAdmin {
	/**
	 * Twinfield plugin object.
	 *
	 * @var Plugin
	 */
	private $plugin;

		/**
		 * Constructs and initialize Twinfield articles admin.
		 */
	public function __construct( Plugin $plugin ) {
		$this->plugin = $plugin;

		// Meta box.
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );

		// Save post
		add_action( 'save_post', array( $this, 'save_post' ) );
	}

	/**
	 * Add meta boxes.
	 */
	public function add_meta_boxes( $post_type ) {
		if ( post_type_supports( $post_type, 'twinfield_invoiceable' ) ) {
			add_meta_box(
				'twinfield_invoice_meta_box',
				__( 'Twinfield Invoice', 'twinfield' ),
				array( $this, 'invoice_meta_box' ),
				$post_type,
				'normal',
				'default'
			);
		}
	}

	/**
	 * Article meta box.
	 */
	public function invoice_meta_box( $post ) {
		wp_nonce_field( 'twinfield_invoice', 'twinfield_invoice_nonce' );

		$invoice = $this->plugin->get_twinfield_sales_invoice_from_post( $post->ID );

		include plugin_dir_path( $this->plugin->file ) . 'admin/meta-box-invoice.php';
	}

	/**
	 * Save post.
	 */
	public function save_post( $post_id ) {
		if ( ! filter_has_var( INPUT_POST, 'twinfield_invoice_nonce' ) ) {
			return;
		}

		if ( ! wp_verify_nonce( filter_input( INPUT_POST, 'twinfield_invoice_nonce' ), 'twinfield_invoice' ) ) {
			return;
		}

		if ( ! post_type_supports( get_post_type( $post_id ), 'twinfield_invoiceable' ) ) {
			return;
		}

		$twinfield_invoice_number = filter_input( INPUT_POST, 'twinfield_invoice_number', FILTER_SANITIZE_STRING );
		if ( empty( $twinfield_invoice_number ) ) {
			delete_post_meta( $post_id, '_twinfield_invoice_number' );
		} else {
			update_post_meta( $post_id, '_twinfield_invoice_number', $twinfield_invoice_number );
		}

		if ( filter_has_var( INPUT_POST, 'twinfield_create_invoice' ) ) {
			$sales_invoice = $this->plugin->get_twinfield_sales_invoice_from_post( $post_id );

			$client = $this->plugin->get_client();

			$xml_processor = $client->get_xml_processor();

			$service = new \Pronamic\WP\Twinfield\SalesInvoices\SalesInvoiceService( $xml_processor );

			$response = $service->insert_sales_invoice( $sales_invoice );

			if ( $response ) {
				if ( $response->is_successful() ) {
					$sales_invoice = $response->get_sales_invoice();

					update_post_meta( $post_id, '_twinfield_invoice_number', $sales_invoice->get_header()->get_number() );

					delete_post_meta( $post_id, '_twinfield_response_xml' );
				} else {
					update_post_meta( $post_id, '_twinfield_response_xml', $response->get_message()->asXML() );
				}
			}
		}
	}
}
