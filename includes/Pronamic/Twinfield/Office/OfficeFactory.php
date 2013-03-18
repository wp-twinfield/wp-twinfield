<?php

namespace Pronamic\Twinfield\Office;

/**
 * Factory class for interacting
 */
class OfficeFactory extends \Pronamic\Twinfield\Secure\Login {

	public function getAll() {
		// Make the document
		$document = new \DOMDocument();

		// Make the elements for this document
		$list = $document->createElement( 'list' );
		$type = $document->createElement( 'type', 'offices' );

		// Append the list to the document
		$document->appendChild( $list );

		// Append type to the list
		$list->appendChild( $type );

		// Process the request
		$result = $this->getClient()->ProcessXmlString( array(
			'xmlRequest' => $document->saveXML()
		) );

		// Make a new domdocument for the response
		$response = new \DOMDocument();
		$response->loadXML( $result->ProcessXmlStringResult );

		// Offices from the response
		$response_offices = $response->getElementsByTagName( 'office' );

		// Holds the office classes
		$offices = array();

		// Loop through them, and make the classes
		foreach ( $response_offices as $office ) {
			foreach ( $office->childNodes as $child ) {

				// Make a new office
				$temp_office = new Office();

				// Set the properties
				$temp_office->setCode( $child->nodeValue );
				$temp_office->setName( $office->getAttribute( 'name' ) );
				$temp_office->setShortName( $office->getAttribute( 'shortname' ) );

				// Store in the array
				$offices[] = $temp_office;

				// free the memory
				unset( $temp_office );
			}
		}

		return $offices;
	}
}