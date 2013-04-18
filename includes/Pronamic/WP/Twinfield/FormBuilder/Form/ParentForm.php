<?php

namespace Pronamic\WP\Twinfield\FormBuilder\Form;

use \Pronamic\Twinfield\Response\Response;

abstract class ParentForm {
	private $response;

	abstract public function extra_variables();
	abstract public function fill_class();

	public function set_response( Response $response ) {
		$this->response = $response;
	}

	public function get_response() {
		return $this->response;
	}

}