<?php

namespace Pronamic\Twinfield\Invoice;

class InvoicesDocument extends \DOMDocument {

	private $salesInvoicesElement;

	public function __construct() {
		parent::__construct();

		$this->salesInvoicesElement = $this->createElement( 'salesinvoices' );
		$this->appendChild($this->salesInvoicesElement);
	}

	public function getNewInvoice() {
		$salesInvoiceElement = $this->createElement('salesinvoice');
		$this->salesInvoicesElement->appendChild($salesInvoiceElement);
		return $salesInvoiceElement;
	}
}
