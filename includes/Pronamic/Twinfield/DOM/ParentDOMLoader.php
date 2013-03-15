<?php

namespace Pronamic\Twinfield\DOM;

/**
 * ParentDOMLoader
 *
 * Builds the DOMDocument Object with the surrounding
 * element as passed up from the child class;
 *
 * Functions:
 * ---------
 *
 * prepare()
 *
 * ---------
 *
 * @author Leon Rowland <leon@rowland.nl>
 * @version 0.0.1
 */
class ParentDOMLoader {

	public function __construct( $surrounding_element ) {
		$this->XML = new \DOMDocument();

		$element = $this->XML->createElement( $surrounding_element );

		$this->XML->appendChild( $element );
	}

	public function prepare() {
		return $this->XML->saveXML();
	}
}