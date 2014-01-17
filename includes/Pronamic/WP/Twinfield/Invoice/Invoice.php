<?php

namespace Pronamic\WP\Twinfield\Invoice;

use \ZFramework\Base\View;

class Invoice {

	public function __construct() {
		add_action( 'generate_rewrite_rules', array( $this, 'generate_rewrite_rules' ) );
		add_action( 'query_vars', array( $this, 'query_vars' ) );

		add_action( 'template_redirect', array( $this, 'render_invoice' ) );
        
        add_action( 'admin_init', array( $this, 'admin_init' ) );
	}
    
    public function admin_init() {
        new InvoiceMetaBox();
    }

	public function generate_rewrite_rules( $wp_rewrite ) {
		$rules = array();

		// Get the invoice slug from options
		$slug = get_option( 'twinfield_invoice_slug', _x( 'invoice', 'Invoice slug for front end', 'twinfield' ) );
		$default_type = get_option( 'wp_twinfield_default_invoice_type', 'FACTUUR' );
        
        $rules[$slug . '/([^/]+)$'] = 'index.php?twinfield_sales_invoice_type=' . $default_type . '&twinfield_sales_invoice_id=' . $wp_rewrite->preg_index(1);
		$rules[$slug . '/([^/]+)/([^/]+)$'] = 'index.php?twinfield_sales_invoice_type=' . $wp_rewrite->preg_index(2) . '&twinfield_sales_invoice_id=' . $wp_rewrite->preg_index(1);

		$wp_rewrite->rules = array_merge( $rules, $wp_rewrite->rules );
	}

	public function query_vars( $query_vars ) {
		$query_vars[] = 'twinfield_sales_invoice_id';
        $query_vars[] = 'twinfield_sales_invoice_type';
		return $query_vars;
	}

	public function render_invoice() {
		$invoice_id = get_query_var( 'twinfield_sales_invoice_id' );
        $invoice_type = get_query_var( 'twinfield_sales_invoice_type' );

		if ( empty( $invoice_id ) || empty( $invoice_type ) )
			return;
		
		if ( ! is_user_logged_in() || ! current_user_can( 'twinfield_read_invoice' ) )
			wp_redirect( wp_login_url( site_url( get_option( 'twinfield_invoice_slug', _x( 'invoice', 'Invoice slug for front end', 'twinfield' ) ) . '/' . $invoice_id ) ) );
		

		global $twinfield_config;
		$invoiceFactory = new \Pronamic\Twinfield\Invoice\InvoiceFactory( $twinfield_config );

		// Make the request
		$invoice = $invoiceFactory->get( $invoice_type, $invoice_id,  $twinfield_config->getOffice() );

		if ( $invoiceFactory->getResponse()->isSuccessful() ) {

			global $twinfield_invoice;
			$twinfield_invoice = $invoice;

			// Generate view from invoice
			$view = new View( PRONAMIC_TWINFIELD_FOLDER . '/views/Pronamic/WP/Invoice' );
			$view
					->setView( 'render_invoice' )
					->setVariable( 'invoice', $invoice )
					->render();

			exit;
		} else {
			include( get_404_template() );
			exit;
		}
	}
}