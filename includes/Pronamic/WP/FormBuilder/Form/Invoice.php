<?php

namespace Pronamic\WP\FormBuilder\Form;

use Pronamic\Twinfield\Customer\Customer;
use Pronamic\Twinfield\Invoice as I;

class Invoice extends ParentForm {

	public function submit() {

		$invoice = $this->fillClass();
		$invoiceElement = new I\InvoiceElement( $invoice );

		parent::submit($invoiceElement);

	}

	public function getSuccessMessage() {
		return 'Form was successful';
	}

	public function fillClass( $data = null ) {
		if ( ! $data )
			$data = $_POST;

		$defaultData = array(
			'customerID' => '',
			'invoiceType' => ''
		);

		$data = array_merge( $defaultData, $data );

		$customer = new Customer();
		$invoice = new I\Invoice();

		if ( empty( $data ) ) {
			$invoice->setCustomer($customer);
			return $invoice;
		}

		$customer->setID( filter_var( $data['customerID'], FILTER_VALIDATE_INT ) );

		$invoice
			->setInvoiceType( filter_var( $data['invoiceType'], FILTER_SANITIZE_STRING ) )
			->setCustomer($customer);

		if ( ! empty( $data['lines'] ) ) {

			$defaultLineData = array(
				'article' => '',
				'quantity' => '',
				'subarticle' => '',
				'units' => '',
				'unitspriceexcl' => '',
				'vatcode' => ''
			);

			foreach ( $data['lines'] as $line ) {
				$line = array_merge( $defaultLineData, $line );

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
		}


		return $invoice;

	}
}