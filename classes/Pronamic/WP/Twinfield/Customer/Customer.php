<?php

namespace Pronamic\WP\Twinfield\Customer;

class Customer {

	public function __construct() {
		add_action( 'generate_rewrite_rules', array( $this, 'generate_rewrite_rules' ) );
		add_action( 'query_vars', array( $this, 'query_vars' ) );

		add_action( 'template_redirect', array( $this, 'render_customer' ) );
		
		add_filter( 'wp_title', array( $this, 'wp_title' ) );
		// Start the Metabox
		$metabox = new CustomerMetaBox();

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
		$slug = get_option( 'twinfield_customer_slug', _x( 'customer', 'Customer slug for frontend', 'twinfield' ) );
		
		$rules[$slug . '/([^/]+)$'] = 'index.php?twinfield_debtor_id=' . $wp_rewrite->preg_index(1);
		
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
		
		if ( ! is_user_logged_in() || ! current_user_can( 'twinfield_read_customer' ) )
			wp_redirect( wp_login_url( site_url( get_option( 'twinfield_customer_slug', _x( 'customer', 'Customer slug for frontend', 'twinfield' ) ) . '/' . $customer_id ) ) );
		
		global $twinfield_config;
		$customerFactory = new \Pronamic\Twinfield\Customer\CustomerFactory( $twinfield_config );

		$customer = $customerFactory->get( $customer_id );

		if ( $customerFactory->getResponse()->isSuccessful() ) {

			global $twinfield_plugin;
			global $twinfield_customer;

			$twinfield_customer = $customer;
			
			$twinfield_plugin->display( 'templates/customer.php', array(
				'twinfield_customer' => $twinfield_customer,
			) );
				
			exit;
		} else {
			include ( get_404_template() );
			exit;
		}
	}
}