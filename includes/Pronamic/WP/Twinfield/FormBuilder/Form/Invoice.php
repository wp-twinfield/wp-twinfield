<?php

namespace Pronamic\WP\Twinfield\FormBuilder\Form;

class Invoice extends ParentForm {

	public function prepare_extra_variables() {}
	
	public function submit( $data = array() ) {

		global $twinfield_config;

		$invoice_factory = new \Pronamic\Twinfield\Invoice\InvoiceFactory( $twinfield_config );

		if ( $invoice_factory->send( $this->fill_class( $data ) ) ) {
			$this->set_response( $invoice_factory->getResponse() );
			return true;
		} else {
			$this->set_response( $invoice_factory->getResponse() );
			return false;
		}

	}

	public function fill_class( array $data ) {

		$defaultData = array(
			'customerID' => '',
			'invoiceType' => ''
		);

		$data = array_merge( $defaultData, $data );

		$customer = new \Pronamic\Twinfield\Customer\Customer();
		$invoice = new \Pronamic\Twinfield\Invoice\Invoice();

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

				if ( ! isset( $line['active'] ) || ! filter_var( $line['active'], FILTER_VALIDATE_BOOLEAN ) )
					continue;

				$line = array_merge( $defaultLineData, $line );

				$temp_line = new \Pronamic\Twinfield\Invoice\InvoiceLine();
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