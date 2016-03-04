<?php

namespace Pronamic\WP\Twinfield\Plugin;

use Pronamic\WP\Twinfield\SalesInvoices\SalesInvoice;
use Pronamic\WP\Twinfield\SalesInvoices\SalesInvoiceStatus;

class InvoicesAdmin {
	/**
	 * Twinfield plugin object.
	 *
	 * @var Plugin
	 */
	private $plugin;

	//////////////////////////////////////////////////

	/**
	 * Constructs and initialize Twinfield articles admin.
	 */
	public function __construct( Plugin $plugin ) {
		$this->plugin = $plugin;

		// Meta box.
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
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

		$invoice_number = get_post_meta( $post->ID, '_twinfield_invoice_number', true );
		$customer_id    = get_post_meta( $post->ID, '_twinfield_customer_id', true );
		$response       = get_post_meta( $post->ID, '_twinfield_response', true );

		$invoice = new SalesInvoice();

		$header = $invoice->get_header();

		$header->set_office( get_option( 'twinfield_default_office_code' ) );
		$header->set_type( get_option( 'twinfield_default_invoice_type' ) );
		$header->set_customer( $twinfield_customer );
		$header->set_status( SalesInvoiceStatus::STATUS_CONCEPT );
		$header->set_footer_text( sprintf(
			__( 'Invoice created by WordPress on %s.', 'twinfield' ),
			date_i18n( 'D j M Y @ H:i' )
		) );

		$invoice = apply_filters( 'twinfield_post_sales_invoice', $invoice, $post->ID );

		include plugin_dir_path( $this->plugin->file ) . 'admin/meta-box-invoice.php';
	}
}
