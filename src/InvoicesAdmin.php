<?php

namespace Pronamic\WP\Twinfield\Plugin;

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

		include plugin_dir_path( $this->plugin->file ) . 'admin/meta-box-invoice.php';
	}
}
