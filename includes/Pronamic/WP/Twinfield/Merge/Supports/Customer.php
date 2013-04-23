<?php

namespace Pronamic\WP\Twinfield\Merge\Supports;

/**
 * Merge Supports: Customer class
 * 
 * Used to make the responses, and automate the merging
 * of customers from a custom meta field value ( current_field )
 * with a new meta field value ( new_field )
 * 
 * @since 0.0.1
 * 
 * @package Pronamic\WP\Twinfield\Merge
 * @subpackage Supports
 * @author Leon Rowland <leon@rowland.nl>
 * @copyright (c) 2013, Leon Rowland
 * @version 0.0.1
 */
use \ZFramework\Base\View;
use \ZFramework\Util\Notice;
use \Pronamic\Twinfield\Customer\CustomerFactory;

class Customer extends BaseSupport {

	public $customers = array( );

	/**
	 * Abstract method create_response from parent BaseSupport
	 * 
	 * Generates an output for all responded customers from the factory
	 * and all post thats that have a match of the custom field
	 * 
	 * @since 0.0.1
	 * 
	 * @access public
	 * @return void
	 */
	public function create_response() {
		// New alert
		$notice = new Notice();
		$notice->error( __( 'Customer requires CoC Number as Custom Meta Field' ) )->get();

		// Get all customers for the response
		$customers = $this->get_customers( $this->getLimit(), $this->getOffset() );

		// Make a partial response view
		$partial_response_view = new View( PRONAMIC_TWINFIELD_FOLDER . '/views/Pronamic/WP/Merge/Customer' );
		$partial_response_view
				->setView( 'partial_response' )
				->setVariable( 'customers', $customers );

		// Get all the matched customers for the response
		$matches = $this->get_matches( $customers );

		// View class
		$partial_table_view = new View( PRONAMIC_TWINFIELD_FOLDER . '/views/Pronamic/WP/Merge/Customer' );
		$partial_table_view
				->setView( 'partial_table' )
				->setVariable( 'posts', $this->posts_query )
				->setVariable( 'new_meta_key', $this->getNewField() )
				->setVariable( 'meta_field', $this->getCurrentField() )
				->setVariable( 'matches', $matches );

		// Make the final view, with the partials from the previous views
		$response_view = new View( PRONAMIC_TWINFIELD_FOLDER . '/views/Pronamic/WP/Merge/Customer' );
		$response_view
				->setView( 'create_response' )
				->setVariable( 'partial_response', $partial_response_view->retrieve() )
				->setVariable( 'partial_table', $partial_table_view->retrieve() )
				->render();
	}

	/**
	 * Abstract method automate from parent BaseSupport
	 * 
	 * Automatically updates all found matches with the linked new_field
	 */
	public function automate() {
		set_time_limit( 0 );

		$customer_factory = new CustomerFactory( $this->getConfig() );

		$customers = $this->get_customers_list();

		$query = new \WP_Query( array(
			'post_type'	 => 'any',
			'meta_query' => array(
				array(
					'key'		 => $this->getCurrentField(),
					'compare'	 => 'EXISTS'
				)
			)
		) );
		
		$matches = array();
		foreach ( $query->posts as $post ) {
			$matches[get_post_meta( $post->ID, $this->getCurrentField(), true )] = array(
				'post' => $post,
				'customer' => false
			);
		}

		
		foreach ( $customers as $code => $info ) {
			$customer = $customer_factory->get( $code, $this->getConfig()->getOffice() );
			
			if ( array_key_exists( $customer->getCocNumber(), $matches ) ) {
				$matches[$customer->getCocNumber()]['customer'] = $customer;
			}
		}
		
		foreach ( $matches as $coc_number => $match ) {
			if ( false !== $match['customer'] ) {
				update_post_meta( $match['post']->ID, $this->getNewField(), $match['customer']->getID() );
			}
		}
	}

	private function get_customers_list() {
		// Get all customers with the limit
		$customer_factory = new CustomerFactory( $this->getConfig() );

		// Get all customer ids
		return $customer_factory->listAll();
	}

	private function get_customers( $limit, $offset ) {
		// Get all customers with the limit
		$customer_factory = new CustomerFactory( $this->getConfig() );

		$customers = $this->get_customers_list();

		// Get the chunk of customers to go through
		$do_customers = array_slice( $customers, $offset, $limit, true );

		// Make an individual request per customer ( there is no finder support )
		if ( ! empty( $do_customers ) ) {
			foreach ( $do_customers as $code => $info ) {
				$customer										 = $customer_factory->get( $code, $this->getConfig()->getOffice() );

				$this->customers[ $customer->getCocNumber() ]	 = $customer;
			}
		}

		return $this->customers;
	}

	private function get_matches( $customers ) {
		// Get all posts with that metafield
		$this->posts_query = new \WP_Query( array(
			'post_type'	 => 'any',
			'meta_query' => array(
				array(
					'key'		 => $this->getCurrentField(),
					'compare'	 => 'EXISTS'
				)
			)
		) );

		$matches = array( );
		if ( $this->posts_query->have_posts() ) {
			while ( $this->posts_query->have_posts() ) {
				$this->posts_query->the_post();

				// Get the custom field
				$coc_number	 = get_post_meta( get_the_ID(), $this->getCurrentField(), true );
				$existing	 = get_post_meta( get_the_ID(), $this->getNewField(), true );

				// Check if the custom field key exists ( the CoC/KvK number )
				if ( empty( $existing ) && array_key_exists( $coc_number, $customers ) ) {

					// WE HAVE A MATCH!
					$matches[ get_the_ID() ] = $customers[ $coc_number ];
				}
			}

			wp_reset_postdata();
		}

		return $matches;
	}

}