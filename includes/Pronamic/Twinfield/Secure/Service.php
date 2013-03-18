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
abstract class Service extends Login {

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
	 * @param \DOMDocument $document A Requests Document
	 * @return \DOMDocument The response from the request
	 */
	public function send( \DOMDocument $document ) {
		// Get the secureclient and send this documents xml
		$result = $this->getClient()->ProcessXmlString( array(
			'xmlRequest' => $document->saveXML()
		) );

		// Make a new DOMDocument, and load the response into it
		$this->response = new \DOMDocument();
		$this->response->loadXML( $result->ProcessXmlStringResult );

		return $this->response;
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