<?php

namespace Pronamic\WP\Twinfield\Customer;

use \ZFramework\Base\View;

class Customer {

	public function __construct() {
		add_action( 'generate_rewrite_rules', array( $this, 'generate_rewrite_rules' ) );
		add_action( 'query_vars', array( $this, 'query_vars' ) );

		add_action( 'template_redirect', array( $this, 'render_customer' ) );
		
		add_filter( 'wp_title', array( $this, 'wp_title' ) );
		// Start the Metabox
		$metabox = new CustomerMetaBox();
		$shortcode = new Shortcode\CustomerShortcode;

	}
	
	function wp_title( $title ) {
		global $twinfield_customer;
		
		if ( isset( $twinfield_customer ) ) {
			$title = $twinfield_customer->getName() . ' - ';
		}
		
		return $title;
	}
	
	public function generate_rewrite_rules( $wp_rewrite ) {
		$rules = array();
		
		// Get the customer slug from options
		$slug = get_option( 'wp_twinfield_customer_slug', _x( 'customer', 'Customer slug for frontend', 'twinfield' ) );
		
		$rules[$slug . '/([^/]+)$'] = 'index.php?pid=11&twinfield_debtor_id=' . $wp_rewrite->preg_index(1);
		
		$wp_rewrite->rules = array_merge( $rules, $wp_rewrite->rules );
	}
	
	public function query_vars( $query_vars ) {
		$query_vars[] = 'twinfield_debtor_id';
		return $query_vars;
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