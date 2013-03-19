<?php

namespace Pronamic\WP\FormBuilder;

class FormBuilder {

	private $valid_forms = array(
		'invoice' => 'Pronamic\Twinfield\Invoice\Invoice'
	);


	public function __construct() {
		add_action( 'admin_init', array( $this, 'create_form' ) );
		add_action( 'admin_init', array( $this, 'listen' ) );
	}

	public function create_form() {
		if ( ! isset( $_GET['page'] ) )
			return;

		if ( $_GET['page'] != 'twinfield_form_builder' )
			return;

		if ( ! isset( $_GET['form'] ) )
			return;

		if ( ! array_key_exists( $_GET['form'], $this->valid_forms ) )
			return;

		try {

			$reflection = new \ReflectionClass( $this->valid_forms[$_GET['form']] );
			var_dump($reflection->getProperties());

		} catch( Exception $e ) {
			var_dump($e);
		}

	}

	public function listen() {
		$customer = new \Pronamic\Twinfield\Customer\Customer();
		$customer->setID( filter_input( INPUT_POST, 'customerID', FILTER_VALIDATE_INT ) );

		//
		$line = new \Pronamic\Twinfield\Invoice\InvoiceLine();
		$line
			->setQuantity( filter_input( INPUT_POST, 'quantity', FILTER_VALIDATE_INT ) )
			->setArticle( filter_input( INPUT_POST, 'article', FILTER_VALIDATE_INT ) );

		// Invoice
		$invoice = new \Pronamic\Twinfield\Invoice\Invoice();
		$invoice
			->setType( filter_input( INPUT_POST, 'invoiceType', FILTER_SANITIZE_STRING ) )
			->addLine( $line )
			->setCustomer($customer);

		// Factory
		$service = new \Pronamic\Twinfield\Secure\Service();

		// DOM DOcument/ELements
		$invoiceElement = new \Pronamic\Twinfield\Invoice\InvoiceElement( $invoice );

		// Send request
		$service->send($invoiceElement);



	}

}