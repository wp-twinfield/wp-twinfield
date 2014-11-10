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
        add_action( 'wp_ajax_twinfield_invoice_metabox_sync', array( $this, 'ajax' ) );
    }

    /**
     * Adds the wp_twinfield_invoice_meta_box on all post types that support
     * 'twinfield_invoiceable'
     *
     * @access public
     * @return void
     */
    public function add_meta_box() {
        foreach ( get_post_types() as $post_type ) {
            if ( post_type_supports( $post_type, 'twinfield_invoiceable' ) ) {
                add_meta_box(
                    'wp_twinfield_invoice_meta_box',
                    __( 'Twinfield Invoice', 'twinfield' ),
                    array( $this, 'view' ),
                    $post_type,
                    'normal',
                    'high'
                );
            }
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
        wp_enqueue_script( 'twinfield_sync' );

        $invoice_id   = get_post_meta( $post->ID, '_twinfield_invoice_id', true );
        $customer_id  = get_post_meta( $post->ID, '_twinfield_customer_id', true );
        $invoice_type = get_post_meta( $post->ID, '_twinfield_invoice_type', true );

        $is_supported = InvoiceMetaBoxFactory::supported( $post->post_type );

        global $twinfield_plugin;

        $twinfield_plugin->display( 'views/meta-box-invoice.php', array(
        	'invoice_id'   => $invoice_id,
        	'customer_id'  => $customer_id,
        	'invoice_type' => $invoice_type,
        	'is_supported' => $is_supported,
        ) );
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
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
            return;

        $customer_id  = filter_input( INPUT_POST, 'twinfield_customer_id', FILTER_SANITIZE_NUMBER_INT );
        $invoice_type = filter_input( INPUT_POST, 'twinfield_invoice_type', FILTER_SANITIZE_STRING );

        if ( ! $invoice_id = $this->check_for_invoice_id( $post_id ) )
            $invoice_id = filter_input( INPUT_POST, 'invoice_id', FILTER_SANITIZE_NUMBER_INT );

        update_post_meta( $post_id, '_twinfield_invoice_id', $invoice_id );
        update_post_meta( $post_id, '_twinfield_customer_id', $customer_id );
        update_post_meta( $post_id, '_twinfield_invoice_type', $invoice_type );

        if ( InvoiceMetaBoxFactory::supported( $post->post_type ) ) {
            $synchronizer = InvoiceMetaBoxFactory::get( $post->post_type );
            $data_proxy = $synchronizer->prepare_sync( $post_id, $customer_id, $invoice_id, $invoice_type );

            $this->sync( $post_id, $data_proxy );
        }
    }

    /**
     * Handles the ajax callback when the action 'twinfield_invoice_metabox_sync' is triggered.
     * Will do a final check that the post type is supported and then make the synchronization
     * attempt.
     *
     * @acccess public
     * @return void
     */
    public function ajax() {
        if ( ! filter_has_var( INPUT_POST, 'post_id' ) )
            echo json_encode( array( 'ret' => false ) );

        $post_id      = filter_input( INPUT_POST, 'post_id', FILTER_SANITIZE_NUMBER_INT );
        $customer_id  = filter_input( INPUT_POST, 'customer_id', FILTER_SANITIZE_NUMBER_INT );
        $invoice_type = filter_input( INPUT_POST, 'invoice_type', FILTER_SANITIZE_STRING );

        if ( ! $invoice_id = $this->check_for_invoice_id( $post_id ) )
            $invoice_id = filter_input( INPUT_POST, 'invoice_id', FILTER_SANITIZE_NUMBER_INT );

        update_post_meta( $post_id, '_twinfield_invoice_id', $invoice_id );
        update_post_meta( $post_id, '_twinfield_customer_id', $customer_id );
        update_post_meta( $post_id, '_twinfield_invoice_type', $invoice_type );

        $post = get_post( $post_id );

        if ( InvoiceMetaBoxFactory::supported( $post->post_type ) ) {
            $synchronizer = InvoiceMetaBoxFactory::get( $post->post_type );
            $data_proxy = $synchronizer->prepare_sync( $post_id, $customer_id, $invoice_id, $invoice_type );

            $this->sync( $post_id, $data_proxy );
        }
    }

    /**
     * Submits the FormBuilder Invoice form and calls the successful
     * method if the state returned was true.
     *
     * Will echo out messages if an ajax request.
     *
     * @access public
     * @param int $post_id
     * @param \Pronamic\WP\Twinfield\FormBuilder\Form\Invoice $form_invoice
     */
    public function sync( $post_id, FormInvoice $form_invoice ) {
        $state = $form_invoice->submit();

        if ( true === $state ) {
            $invoice = \Pronamic\Twinfield\Invoice\Mapper\InvoiceMapper::map( $form_invoice->get_response() );
            $this->successful( $post_id, $invoice );
        }

        if ( is_ajax() ) {
            if ( true === $state ) {
                echo json_encode( array(
                    'ret' => true,
                    'msg' => __( 'Successfully synced', 'twinfield' )
                ) );
            } else {
                echo json_encode( array(
                    'ret'  => false,
                    'msgs' => $form_invoice->get_response()->getErrorMessages()
                ) );
            }

            exit;
        }
    }

    /**
     * Takes the Invoice entity and sets the post meta values from the
     * response.
     *
     * @access public
     * @param int $post_id
     * @param \Pronamic\Twinfield\Invoice\Invoice $invoice
     */
    public function successful( $post_id, InvoiceEntity $invoice ) {
        update_post_meta( $post_id, '_twinfield_invoice_id', $invoice->getInvoiceNumber() );
        update_post_meta( $post_id, '_twinfield_customer_id', $invoice->getCustomer()->getID() );
        update_post_meta( $post_id, '_twinfield_invoice_type', $invoice->getInvoiceType() );
    }

    /**
     * Returns the _twinfield_invoice_id if exists, or false if not.
     *
     * @access private
     * @param int $post_id
     * @return string|false
     */
    private function check_for_invoice_id( $post_id ) {
        return get_post_meta( $post_id, '_twinfield_invoice_id', true );
    }
}
