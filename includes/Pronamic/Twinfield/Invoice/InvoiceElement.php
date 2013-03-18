<?php

namespace Pronamic\Twinfield\Invoice;

class InvoiceElement extends InvoicesDocument {

	public function __construct( Invoice $invoice ) {
		parent::__construct();

		$invoiceElement = $this->getNewInvoice();

		// Makes header element
		$headerElement = $this->createElement( 'header' );
		$invoiceElement->appendChild( $headerElement );

		// Set customer element
		$customer = $invoice->getCustomer();
		$customerElement = $this->createElement( 'customer', $customer->getID() );
		$headerElement->appendChild( $customerElement );

		// Set invoicetype element
		$invoiceTypeElement = $this->createElement( 'invoicetype', $invoice->getType() );
		$headerElement->appendChild( $invoiceTypeElement );

		// Add orders
		$linesElement = $this->createElement( 'lines' );
		$invoiceElement->appendChild( $linesElement );

		// Loop through all orders, and add those elements
		foreach ( $invoice->getLines() as $line ) {

			// Make a new line element, and add to <lines>
			$lineElement = $this->createElement( 'line' );
			$linesElement->appendChild( $lineElement );

			// Set attributes
			$quantityElement		 = $this->createElement( 'quantity', $line->getQuantity() );
			$articleElement			 = $this->createElement( 'article', $line->getArticle() );
			$subarticleElement		 = $this->createElement( 'subarticle', $line->getSubArticle() );
			$descriptionElement		 = $this->createElement( 'description', $line->getDescription() );
			$unitsPriceExclElement	 = $this->createElement( 'unitspriceexcl', $line->getUnitsPriceExcl() );
			$unitsElement			 = $this->createElement( 'units', $line->getUnits() );
			$vatCodeElement			 = $this->createElement( 'vatcode', $line->getVatCode() );
			$freeText1Element		 = $this->createElement( 'freetext1', $line->getFreeText1() );
			$freeText2Element		 = $this->createElement( 'freetext2', $line->getFreeText2() );

			// Add those attributes to the line
			$lineElement->appendChild( $quantityElement );
			$lineElement->appendChild( $articleElement );
			$lineElement->appendChild( $subarticleElement );
			$lineElement->appendChild( $descriptionElement );
			$lineElement->appendChild( $unitsPriceExclElement );
			$lineElement->appendChild( $unitsElement );
			$lineElement->appendChild( $vatCodeElement );
			$lineElement->appendChild( $freeText1Element );
			$lineElement->appendChild( $freeText2Element );
		}
	}
}