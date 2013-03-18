<?php

namespace Pronamic\Twinfield\Invoice;

/**
 * Invoice Service
 *
 * Is the InvoiceDocument service handler for submitting
 * to the SOAP Service.
 *
 * @since 0.0.1
 *
 * @package Pronamic\Twinfield
 * @subpackage Invoice
 * @author Leon Rowland <leon@rowland.nl>
 * @copyright (c) 2013, Leon Rowland
 * @version 0.0.1
 */

use Pronamic\Twinfield\Secure\Service as SecureService;

class Service extends SecureService {

	/**
	 * Constructor passes in the name of the elements
	 * to check for success messages.
	 */
	public function __construct() {
		parent::__construct();

		$this->setElementsToCheck( array(
			'salesinvoices' => 'result'
		) );
	}
}