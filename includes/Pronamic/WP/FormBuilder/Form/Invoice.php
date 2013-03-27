<?php

namespace Pronamic\WP\FormBuilder\Form;

use Pronamic\Twinfield\Customer\Customer;
use Pronamic\Twinfield\Invoice as I;

class Invoice extends ParentForm {

	public function submit() {

		global $twinfield_config;

		$invoice_factory = new \Pronamic\Twinfield\Invoice\InvoiceFactory( $twinfield_config );

		if ( $invoice_factory->send( $this->fill_class() ) ) {
			return __( 'Successful!', 'twinfield' );
		} else {
			return $invoice_factory->getResponse()->getResponseDocument()->saveXML();
		}

	}

	public function getSuccessMessage() {
		return 'Form was successful';
	}

	public function fill_class( $data = null ) {
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
				'vatcode' => '',
				'freetext1' => '',
				'freetext2' => '',
				'freetext3' => ''
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
						->setFreeText1( $line['freetext1'] )
						->setFreeText2( $line['freetext2'] )
						->setFreeText3( $line['freetext3'] )
						->setVatCode( $line['vatcode'] );

				$invoice->addLine( $temp_line );
			}
		}


		return $invoice;

	}
}