<?php

namespace Pronamic\Twinfield\Secure;

abstract class Service extends Login {

	private $response;

	public function send( \DOMDocument $document ) {

		$result = $this->getClient()->ProcessXmlString( array(
			'xmlRequest' => $document->saveXML()
		) );

		$this->response = new \DOMDocument();
		$this->response->loadXML( $result->ProcessXmlStringResult );
	}

	public function getResponse() {
		return $this->response;
	}

}