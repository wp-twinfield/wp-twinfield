<?php

namespace Pronamic\Twinfield\Invoice;

/**
 * The Document Holder for making new XML invoices.
 * Is an abstract class, and you don't call it directly.
 *
 * It is instead, extended by the Elements class for
 * each component.
 *
 * @uses \Pronamic\Twinfield\Secure\Document Is required to extend as part of the checking methods
 *
 * @since 0.0.1
 *
 * @package Pronamic\Twinfield
 * @subpackage Invoice
 * @author Leon Rowland <lon@rowland.nl>
 * @copyright (c) 2013, Leon Rowland
 * @version 0.0.1
 */
use \Pronamic\Twinfield\Secure\Document as SecureDocument;

abstract class InvoicesDocument extends SecureDocument {

	private $salesInvoicesElement;

	public function __construct() {
		parent::__construct();

		// Set elements to check in the securedocument
		$this->setElementsToCheck( 'salesinvoices' );

		// Make the main wrap element
		$this->salesInvoicesElement = $this->createElement( 'salesinvoices' );
		$this->appendChild($this->salesInvoicesElement);
	}

	/**
	 * Creates the new \DOMElement for a salesinvoice,
	 * assigns it to this \DOMDocument and returns it.
	 *
	 * @since 0.0.1
	 *
	 * @access public
	 * @return \DOMElement A <salesinvoice> DOMElement
	 */
	public function getNewInvoice() {
		// Make the new salesinvoice element
		$salesInvoiceElement = $this->createElement('salesinvoice');

		// Add to the main salesinvoices element
		$this->salesInvoicesElement->appendChild($salesInvoiceElement);

		// Return the saleinvoice element
		return $salesInvoiceElement;
	}
}
