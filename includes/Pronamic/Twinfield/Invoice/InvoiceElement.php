<?php

namespace Pronamic\Twinfield\Invoice;

class InvoiceElement extends InvoicesDocument {

	public function __construct( Invoice $invoice ) {
		parent::__construct();

		// Makes header element
		$headerElement = $this->createElement( 'header' );
		$this->appendChild( $headerElement );

		// Set customer element
		$customer = $invoice->getCustomer();
		$customerElement = $this->createElement( 'customer', $customer->getID() );
		$headerElement->appendChild( $customerElement );

		// Set invoicetype element
		$invoiceTypeElement = $this->createElement( 'invoicetype', $invoice->getType() );
		$headerElement->appendChild( $invoiceTypeElement );

		// Add orders
		$linesElement = $this->createElement( 'lines' );
		$this->appendChild( $linesElement );

		// Loop through all orders, and add those elements
		foreach ( $invoice->getOrders() as $order ) {

			// Make a new line element, and add to <lines>
			$lineElement = $this->createElement( 'line' );
			$linesElement->appendChild( $lineElement );

			// Set attributes
			$quantityElement		 = $this->createElement( 'quantity', $order->getQuantity() );
			$articleElement			 = $this->createElement( 'article', $order->getArticle() );
			$subarticleElement		 = $this->createElement( 'subarticle', $order->getSubArticle() );
			$descriptionElement		 = $this->createElement( 'description', $order->getDescription() );
			$unitsPriceExclElement	 = $this->createElement( 'unitspriceexcl', $order->getUnitsPriceExcl() );
			$unitsElement			 = $this->createElement( 'units', $order->getUnits() );
			$vatCodeElement			 = $this->createElement( 'vatcode', $order->getVatCode() );
			$freeText1Element		 = $this->createElement( 'freetext1', $order->getFreeText1() );
			$freeText2Element		 = $this->createElement( 'freetext2', $order->getFreeText2() );

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