<?php

namespace Pronamic\WP\Twinfield\Invoice;

use \Pronamic\WP\Twinfield\FormBuilder\Form\Invoice as FormInvoice;
use \Pronamic\Twinfield\Invoice\Invoice as InvoiceEntity;

/**
 * InvoiceMetaBox adds the Twinfield Invoice meta box to the post types
 * that declare their support for it.
 *
 * Looks for post types that have twinfield_invoiceable as a support
 * and shows the meta box.  The showing of the meta box queues the
 * WP_Twinfield_Sync javascript library.
 *
 * If also the InvocieMetaBoxFactory has registered the post type
 * with a class to handle the prepare_sync method then a synchronize
 * button will show up in the meta box.
 *
 * Registers an AJAX action 'twinfield_invoice_metabox_sync' where it
 * will also handle the submission of the Sync ( if supported ) via AJAX
 * as well as on 'save_post' action.
 *
 * For an example of how to support synchronization please see the
 * https://github.com/pronamic/wp-woocommerce-twinfield/tree/develop/lib/class-woocommerce-invoicemetabox.php
 * file.
 *
 * @package Pronamic\WP\Twinfield
 * @subpackage Invoice
 * @author Leon Rowland <leon@rowland.nl>
 * @copyright (c) 2013, Pronamic
 */
class InvoiceMetaBox {

    /**
     * Adds the required actions to show the meta box, intercept the save_post
     * and handle the ajax request for synchronization.
     *
     * @access public
     */
    public function __construct() {
        add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );

        add_action( 'save_post', array( $this, 'save' ), 10, 2 );
        add_action( 'save_post', array( $this, 'save_post' ), 10, 2 );
    }

    public function save_post( $post_id, $post ) {
		if ( filter_has_var( INPUT_POST, 'twinfield_create_invoice' ) ) {
			if ( InvoiceMetaBoxFactory::supported( $post->post_type ) ) {
            	$synchronizer = InvoiceMetaBoxFactory::get( $post->post_type );

            	$data_proxy = $synchronizer->prepare_sync( $post_id );

            	$state = $data_proxy->submit();

            	update_post_meta( $post_id, '_twinfield_response', $data_proxy->get_response()->getResponseDocument()->saveXML() );

            	if ( true === $state ) {
            		$invoice = \Pronamic\Twinfield\Invoice\Mapper\InvoiceMapper::map( $data_proxy->get_response() );

            		update_post_meta( $post_id, '_twinfield_invoice_number', $invoice->getInvoiceNumber() );
            		update_post_meta( $post_id, '_twinfield_invoice_type', $invoice->getInvoiceType() );
            		update_post_meta( $post_id, '_twinfield_customer_id', $invoice->getCustomer()->getID() );
				}
			}
		}
    }

    /**
     * Adds the wp_twinfield_invoice_meta_box on all post types that support
     * 'twinfield_invoiceable'
     *
     * @access public
     * @return void
     */
    public function add_meta_box( $post_type ) {
		if ( post_type_supports( $post_type, 'twinfield_invoiceable' ) ) {
			add_meta_box(
				'wp_twinfield_invoice_meta_box',
				__( 'Twinfield Invoice', 'twinfield' ),
				array( $this, 'view' ),
				$post_type,
				'normal',
				'default'
			);
		}
	}

    /**
     * Shows the contents of the wp_twinfield_invoice_meta_box.  Passes
     * invoice_id, customer_id, invoice_type and is_supported variables to the
     * view.
     *
     * @access public
     * @param WP_Post $post
     * @return void
     */
    public function view( \WP_Post $post ) {
        global $twinfield_plugin;

        $twinfield_plugin->display( 'views/meta-box-invoice.php' );
    }

    /**
     * Saves the contents of the meta box inputs, invoice_id, customer_id and invoice_type.  Will also
     * synchronize if supported.
     *
     * @access public
     * @param int $post_id
     * @param WP_Post $post
     * @return void
     */
    public function save( $post_id, \WP_Post $post ) {
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }

        $invoice_number = filter_input( INPUT_POST, 'twinfield_invoice_number', FILTER_SANITIZE_NUMBER_INT );
        $invoice_type   = filter_input( INPUT_POST, 'twinfield_invoice_type', FILTER_SANITIZE_STRING );
        $customer_id    = filter_input( INPUT_POST, 'twinfield_customer_id', FILTER_SANITIZE_NUMBER_INT );

        update_post_meta( $post_id, '_twinfield_invoice_number', $invoice_number );
        update_post_meta( $post_id, '_twinfield_invoice_type', $invoice_type );
        update_post_meta( $post_id, '_twinfield_customer_id', $customer_id );
    }
}
