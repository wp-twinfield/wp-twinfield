<?php

namespace Pronamic\WP\Customer;

/**
 * CustomerMetaBox Class
 *
 * Used to display the metabox and determine which
 * post type the metabox should show on.
 *
 * To get the metabox to show on your post type, just add
 * 'twinfield_customer' to the supports parameter.
 *
 * Actions used:
 * --------------
 *
 * add_meta_boxes
 * save_post
 *
 * --------------
 *
 * @since 0.0.1
 *
 * @uses \ZFrameowkr\Base\View()
 *
 * @package Pronamic\WP
 * @subpackage Customer
 * @author Leon Rowland <leon@rowland.nl>
 * @copyright (c) 2013, Leon Rowland
 * @version 0.0.1
 */
use \ZFramework\Base\View;

class CustomerMetaBox {
	public function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );

		add_action( 'save_post', array( $this, 'save' ), 10, 2 );
	}

	/**
	 * Determines where to show the meta box. It will
	 * get all post types, loop through and check if the
	 * post type supports 'twinfield_customer'
	 *
	 * @since 0.0.1
	 *
	 * @access public
	 * @return void
	 */
	public function add_meta_boxes() {
		$post_types = get_post_types( '', 'names' );

		foreach ( $post_types as $post_type ) {
			if ( post_type_supports( $post_type, 'twinfield_customer' ) ) {
				add_meta_box(
						'pronamic_twinfield_customer',
						__( 'Twinfield Customer', 'twinfield' ),
						array( $this, 'view' ),
						$post_type,
						'normal',
						'high'
				);
			}
		}
	}

	/**
	 * Displays the metabox contents
	 *
	 * Loads the view file from ~/views/Pronamic/WP/Customer/customermetabox_view
	 *
	 * @since 0.0.1
	 *
	 * @access public
	 * @param WP_Post $post
	 * @return void
	 */
	public function view( $post ) {
		// Get existing saved customer id
		$twinfield_customer_id = get_post_meta( $post->ID, 'twinfield_customer_id', true );

		// Generate the nonce
		$nonce = wp_nonce_field( 'twinfield_customer', 'twinfield_customer_nonce', true, false );

		// Make the view
		$view = new View( dirname( \Twinfield::$file ) . '/views/Pronamic/WP/Customer' );
		$view
			->setVariable( 'nonce', $nonce )
			->setVariable( 'twinfield_customer_id', $twinfield_customer_id )
			->setView( 'customermetabox_view' )
			->render();
	}

	/**
	 * Saves the customer meta box information.
	 *
	 * @since 0.0.1
	 *
	 * @access public
	 * @param string $post_id
	 * @param WP_Post $post
	 * @return void
	 */
	public function save( $post_id, $post ) {
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
			return;

		if ( ! isset( $_POST['twinfield_customer_nonce'] ) )
			return;

		if ( ! current_user_can( 'edit_post', $post_id ) )
			return;

		if ( ! wp_verify_nonce( filter_input( INPUT_POST, 'twinfield_customer_nonce'), 'twinfield_customer' ) )
			return;

		if ( ! post_type_supports( $post->post_type, 'twinfield_customer' ) )
			return;

		// Get posted id
		$customer_id = filter_input( INPUT_POST, 'twinfield_customer_id', FILTER_VALIDATE_INT );

		// Check its a valid int, update it or remove it conditionally
		if ( $customer_id ) {
			update_post_meta( $post_id, 'twinfield_customer_id', $customer_id );
		} else {
			delete_post_meta( $post_id, 'twinfield_customer_id' );
		}

	}
}