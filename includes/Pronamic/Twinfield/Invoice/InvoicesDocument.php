<?php

namespace Pronamic\Twinfield\Invoice;

class InvoicesDocument extends \DOMDocument {

	public function getNewInvoice() {
		return $this->createElement('salesinvoice');
	}
}
