<?php

namespace Pronamic\Twinfield\Secure;

/**
 * Abstract Service Class
 *
 * This is the main class each components Service class extends.
 * It handles the request and response.
 *
 * @uses \DOMDocument
 * @uses \SoapClient
 *
 * @since 0.0.1
 *
 * @package Pronamic\Twinfield
 * @subpackage Secure
 * @author Leon Rowland <leon@rowland.nl>
 * @copyright (c) 2013, Leon Rowland
 * @version 0.0.1
 */
use \Pronamic\Twinfield\Secure\Document as SecureDocument;

class Service extends Login {

	/**
	 * The result from the ProcessXMLString
	 * called to the Twinfield SOAP Service
	 *
	 * @access private
	 * @var XML
	 */
	private $result;

	/**
	 * Holds the response from the a request
	 *
	 * @access private
	 * @var DOMDocument
	 */
	private $response;

	/**
	 * Sends a request with the secured client, and loads
	 * the result response into Service->response
	 *
	 * The response is also returned.
	 *
	 * @since 0.0.1
	 *
	 * @access public
	 * @param Document $document A class that extended Secure\Document
	 * @return \DOMDocument The response from the request
	 */
	public function send( SecureDocument $document ) {

		// Get the secureclient and send this documents xml
		$this->result = $this->getClient()->ProcessXmlString( array(
			'xmlRequest' => $document->saveXML()
		) );

		// Make a new DOMDocument, and load the response into it
		$this->response = new \DOMDocument();
		$this->response->loadXML( $this->result->ProcessXmlStringResult );

		return new \Pronamic\Twinfield\Response\Response( $this->response, $document );
	}

	/**
	 * Returns the DOMDocument response from the latest
	 * send
	 *
	 * @since 0.0.1
	 *
	 * @access public
	 * @return \DOMDocument OR null The response from the latest send()
	 */
	public function getResponse() {
		return $this->response;
	}
}