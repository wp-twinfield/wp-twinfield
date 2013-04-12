<?php

namespace Pronamic\WP\Customer;

use \ZFramework\Base\View;

class Customer {

	public function __construct() {
		
		// Start the Metabox
		$metabox = new CustomerMetaBox();
		$shortcode = new Shortcode\CustomerShortcode;

	}
	
	public function render_customer() {
		$customer_id = get_query_var( 'twinfield_debtor_id' );
		
		if ( empty( $customer_id ) )
			return;
			
		global $twinfield_config;
		$customerFactory = new \Pronamic\Twinfield\Customer\CustomerFactory( $twinfield_config );
		
		$customer = $customerFactory->get( $customer_id );
		
		if ( $customerFactory->getResponse()->isSuccessful() ) {
			
			global $twinfield_customer;
			$twinfield_customer = $customer;
			
			$view = new View( PRONAMIC_TWINFIELD_FOLDER . '/views/Pronamic/WP/Customer' );
			$view
				->setView( 'render_customer' )
				->setVariable( 'customer', $customer )
				->render();
				
			exit;
		} else {
			include ( get_404_template() );
			exit;
		}
	}
}