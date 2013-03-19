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
use Document as SecureDocument;

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

		$elementsToCheck = $document->getElementsToCheck();

		// @todo require an exception
		if ( empty( $elementsToCheck ) )
			throw new Exception\MissingElementsToCheck();

		// @todo require an exception
		if ( ! $this->response )
			throw new Exception\NoResponseFromCluster();

		// Loop through each set checkElement
		foreach ( $elementsToCheck as $element => $attributeName ) {
			// Make a temp DOM element
			$tempElement = $this->response->getElementsByTagName( $element );

			// Multiple elements found
			if ( is_array( $tempElement ) && 1 > count( $tempElement ) ) {
				// Check each element
				foreach ( $tempElement as $tElement ) {
					if ( 1 != $tElement->getAttribute( $attributeName ) )
						throw new Exception\ElementNotPassed( $tElement );
				}

				return true;

			} else {
				// Singular
				$responseValue = $tempElement->item(0)->getAttribute( $attributeName );

				if ( 1 == $responseValue ) {
					return true;
				} else {
					throw new Exception\ElementNotPassed( $tempElement->item(0) );
				}
			}
		}
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