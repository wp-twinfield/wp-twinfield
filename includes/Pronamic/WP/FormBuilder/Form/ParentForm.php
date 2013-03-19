<?php

namespace Pronamic\WP\FormBuilder\Form;

use Pronamic\Twinfield\Secure\Service;
use Pronamic\Twinfield\Secure\Document as SecureDocument;

abstract class ParentForm {
	private $service;

	abstract public function fillClass();
	abstract public function getSuccessMessage();

	public function submit( SecureDocument $document ) {
		$this->service = new Service();

		$this->response = $this->service->send($document);

		if ( ! $this->response->isSuccessful() ) {
			// Get the errors from the response
			$errors = $this->response->getErrors();
			var_dump($errors);
			// Set error messages
			return false;

		} else {
			// set success message
			echo $this->getSuccessMessage();
			return true;
		}
	}
}