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

		// Save post.
		add_action( 'save_post', array( $this, 'save_post' ) );

		// Columns.
		add_filter( 'manage_posts_columns', array( $this, 'manage_posts_columns' ), 100, 2 );

		add_action( 'manage_posts_custom_column', array( $this, 'manage_posts_custom_column' ), 100, 2 );
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
	 * Manage posts columns
	 *
	 * @param array  $posts_columns
	 * @param string $post_type
	 */
	public function manage_posts_columns( $columns, $post_type ) {
		if ( post_type_supports( $post_type, 'twinfield_invoiceable' ) ) {
			$columns['twinfield_invoice'] = __( 'Twinfield Invoice', 'twinfield' );

			$new_columns = array();

			foreach ( $columns as $name => $label ) {
				if ( 'author' === $name ) {
					$new_columns['twinfield_invoice'] = $columns['twinfield_invoice'];
				}

				$new_columns[ $name ] = $label;
			}

			$columns = $new_columns;
		}

		return $columns;
	}

	public function manage_posts_custom_column( $column_name, $post_id ) {
		if ( 'twinfield_invoice' === $column_name ) {
			$invoice_number = get_post_meta( $post_id, '_twinfield_invoice_number', true );

			if ( empty( $invoice_number ) ) {
				echo esc_html( '—' );
			} else {
				echo esc_html( $invoice_number );
			}
		}
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
			$this->plugin->insert_twinfield_sales_invoice_from_post( $post_id );
		}
	}
}
