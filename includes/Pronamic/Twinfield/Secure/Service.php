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

	private $checkElements = array();

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

	/**
	 * Sets the elements to look for a passed
	 * check status on.
	 *
	 * Based off the order you put them in the array determines
	 * on the order it checks.
	 *
	 * It is thus recommended to put elements as they
	 * appear in the response xml.
	 *
	 * You could in effect, just put the highest element to check then.
	 *
	 * Expects an array with the key as the tag element to check
	 * and the value the name of the attribute on that element.
	 *
	 * @since 0.0.1
	 *
	 * @access public
	 * @param array $elements Associative array of element=>attribute
	 */
	public function setElementsToCheck( $elements ) {
		$this->checkElements = $elements;
	}

	/**
	 * Will attempt to check the response for a success attribute.
	 *
	 * Will handle the elements set from setElementsToCheck() in
	 * order they are supplied.
	 *
	 * It is recommended to always have this method in your code.
	 *
	 * @todo Implement custom exceptions
	 *
	 * @since 0.0.1
	 *
	 * @access public
	 * @return boolean If a success was found or not
	 */
	public function passed() {
		// @todo require an exception
		if ( empty( $this->checkElements ) )
			return false;

		// @todo require an exception
		if ( ! $this->response )
			return false;

		// Loop through each set checkElement
		foreach ( $this->checkElements as $element => $attributeName ) {
			// Make a temp DOM element
			$tempElement = $this->response->getElementsByTagName( $element );

			// Multiple elements found
			if ( is_array( $tempElement ) && 1 > count( $tempElement ) ) {
				// Check each element
				foreach ( $tempElement as $tElement ) {
					if ( 1 != $tElement->getAttribute( $attributeName ) )
						return false;
				}

				return true;

			} else {
				// Singular
				$responseValue = $tempElement->item(0)->getAttribute( $attributeName );

				if ( 1 == $responseValue ) {
					return true;
				} else {
					return false;
				}
			}
		}
	}
}