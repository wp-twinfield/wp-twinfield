<?php

namespace Pronamic\WP\Twinfield\Customer\Shortcode;

use \ZFramework\Base\View;

class CustomerShortcode {
	public function __construct() {
		add_shortcode( 'customer', array( $this, 'process_shortcode' ) );
	}
	
	public function process_shortcode( $attributes ) {
		global $twinfield_config;
		
		$defaults = array(
			'id' => null
		);
		
		$atts = shortcode_atts( $defaults, $attributes );
		
		// If no id was supplied in the shortcode, look for the query variable
		if ( ! $atts['id'] ) {
			$atts['id'] = get_query_var( 'twinfield_debtor_id' );
		}
		
		if ( empty( $atts['id'] ) )
			return;
		
		$customer_factory = new \Pronamic\Twinfield\Customer\CustomerFactory( $twinfield_config );
		
		$customer = $customer_factory->get( $atts['id'] );
		$response = $customer_factory->getResponse();
		
		if ( $response->isSuccessful() ) {
			global $twinfield_customer;
			$twinfield_customer = $customer;
			
			$view = new View( PRONAMIC_TWINFIELD_FOLDER . '/views/Pronamic/WP/Customer/Shortcode' );
			
			$view->setView( 'customershortcode-process_shortcode' )->setVariable( 'customer', $customer );
			
			return $view->retrieve();
		} else {
			return 'ops';
		}
	}
}