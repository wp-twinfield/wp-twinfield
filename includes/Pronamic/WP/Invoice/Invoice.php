<?php

namespace Pronamic\WP\Invoice;

use \ZFramework\Base\View;

class Invoice {

	public function __construct() {
		add_action( 'generate_rewrite_rules', array( $this, 'generate_rewrite_rules' ) );
		add_action( 'query_vars', array( $this, 'query_vars' ) );

		add_action( 'template_redirect', array( $this, 'render_invoice' ) );
	}

	public function generate_rewrite_rules( $wp_rewrite ) {
		$rules = array();

		$rules['facturen/([^/]+)$'] = 'index.php?twinfield_sales_invoice_id=' . $wp_rewrite->preg_index(1);
		$rules['debiteuren/([^/]+)$'] = 'index.php?twinfield_debtor_id=' . $wp_rewrite->preg_index(1);

		$wp_rewrite->rules = array_merge( $rules, $wp_rewrite->rules );
	}

	public function query_vars( $query_vars ) {
		$query_vars[] = 'twinfield_sales_invoice_id';
		$query_vars[] = 'twinfield_debtor_id';

		return $query_vars;
	}

	public function render_invoice() {

		$invoice_id = get_query_var( 'twinfield_sales_invoice_id' );

		if ( empty( $invoice_id ) )
			return;

		global $twinfield_config;
		$invoiceFactory = new \Pronamic\Twinfield\Invoice\InvoiceFactory( $twinfield_config );

		// Make the request
		$invoice = $invoiceFactory->get( 'FACTUUR', $invoice_id,  $twinfield_config->getOffice() );

		if ( $invoiceFactory->getResponse()->isSuccessful() ) {

			global $twinfield_invoice;
			$twinfield_invoice = $invoice;

			// Generate view from invoice
			$view = new View( dirname( \Twinfield::$file ) . '/views/Pronamic/WP/Invoice' );
			$view
					->setView( 'render_invoice' )
					->setVariable( 'invoice', $invoice )
					->render();

			exit;
		} else {
			get_404_template();
		}






	}
}