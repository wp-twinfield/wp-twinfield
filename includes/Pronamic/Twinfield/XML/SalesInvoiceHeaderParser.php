<?php

namespace Pronamic\Twinfield\XML;

use Pronamic\Twinfield\SalesInvoiceHeader;

/**
 * Title: Sales invoice header XML parser
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class SalesInvoiceHeaderParser {
	public static function parse(\SimpleXMLElement $xml) {
		$header = new SalesInvoiceHeader();

		$header->setOffice(filter_var($xml->office, FILTER_SANITIZE_STRING));
		$header->setType(filter_var($xml->invoicetype, FILTER_SANITIZE_STRING));
		$header->setInvoiceNumber(filter_var($xml->invoicenumber, FILTER_SANITIZE_STRING));

		return $header;
	}
}
