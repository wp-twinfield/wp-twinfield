<?php

namespace Pronamic\WP\FormBuilder\Form;

use Pronamic\Twinfield\Customer\Customer;
use Pronamic\Twinfield\Invoice as I;
use Pronamic\Twinfield\Secure\Service;

class Invoice {

	public function submitted() {

		$invoice = $this->fillClass();

		// Secure Service Interaction
		$service = new Service();

		// New DOMDocument/Element
		$invoiceElement = new I\InvoiceElement( $invoice );

		// Send request
		$response = $service->send($invoiceElement);

		// Check the response was a successful one
		if ( ! $response->isSuccessful() ) {
			$errors = $response->getErrors();

			foreach ( $errors as $error ) {
				echo $error;
			}

			return false;
		} else {
			return true;
		}
	}

	public function getSuccessMessage() {
		return 'Successful posted Invoice';
	}

	public function getFailedMessage() {
		return 'Failed to post invoice';
	}

	public function fillClass( $data = null ) {
		if ( ! $data )
			$data = $_POST;

		$customer = new Customer();
		$invoice = new I\Invoice();

		if ( empty( $data ) ) {
			$invoice->setCustomer($customer);
			return $invoice;
		}

		$customer->setID( filter_var( $data['customerID'], FILTER_VALIDATE_INT ) );

		$invoice
			->setType( filter_var( $data['invoiceType'], FILTER_SANITIZE_STRING ) )
			->setCustomer($customer);

		foreach ( $data['lines'] as $line ) {
			$temp_line = new I\InvoiceLine();
			$temp_line
					->setArticle( $line['article'] )
					->setQuantity( $line['quantity'] )
					->setSubArticle( $line['subarticle'] )
					->setUnits( $line['units'] )
					->setUnitsPriceExcl( $line['unitspriceexcl'] )
					->setVatCode( $line['vatcode'] );

			$invoice->addLine( $temp_line );
		}

		return $invoice;

	}
}