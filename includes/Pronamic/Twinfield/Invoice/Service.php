<?php

namespace Pronamic\Twinfield\Invoice;

class Service extends \Pronamic\Twinfield\Secure\Login {

	public function sendInvoice( InvoicesDocument $invoicesDocument ) {
		// Submit an InvoicesDocument
		$result = $this->getClient()->ProcessXmlString( array(
			'xmlRequest' => $invoicesDocument->saveXML()
		) );

		$response = new \DOMDocument();
		$response->loadXML( $result->ProcessXmlStringResult );

		$responseInvoices = $response->getElementsByTagName( 'salesinvoices' );
		$totalResponse = $responseInvoices[0]->getAttribute( 'result' );

		if ( 1 == $totalResponse ) {
			// success for all!
		}

		// Get all saleinvoice singular elements
		$responseInvoice = $response->getElementsByTagName( 'salesinvoice' );

		foreach( $responseInvoice as $invoice ) {
			// failed invoice
			if ( 1 != $invoice->getAttribute( 'result' ) ) {
				// something failed
			}
		}
	}
}