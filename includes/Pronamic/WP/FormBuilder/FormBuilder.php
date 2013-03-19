<?php

namespace Pronamic\WP\FormBuilder;

class FormBuilder {

	private $valid_forms = array(
		'invoice' => 'create_form_invoice',

	);

	public function __construct() {
		add_action( 'admin_init', array( $this, 'listen' ) );
	}

	public function get_valid_form_types() {
		return $this->valid_forms;
	}

	public function create_form() {
		if ( ! isset( $_GET['page'] ) )
			return;

		if ( $_GET['page'] != 'twinfield_form_builder' )
			return;

		if ( ! isset( $_GET['form'] ) )
			return;

		if ( ! array_key_exists( $_GET['form'], $this->valid_forms ) )
			return;

		$formClass = new \ReflectionClass( __NAMESPACE__ . "\\Form\\" . ucfirst( $_GET['form'] ) );
		$form = $formClass->newInstance();

		$view = new \ZFramework\Base\View( dirname( \Twinfield::$file ) . '/views/Pronamic/WP/FormBuilder' );
		$view
			->setVariable( 'nonce', wp_nonce_field( 'twinfield_form_builder', 'twinfield_form_nonce' ) )
			->setVariable( $_GET['form'], $form->fillClass( $_POST['twinfield_form_fill'] ) )
			->setView( $this->valid_forms[$_GET['form']] )
			->render();
	}

	public function listen() {
		if ( ! isset( $_GET['page'] ) )
			return;

		if ( $_GET['page'] != 'twinfield_form_builder' )
			return;

		if ( ! isset( $_GET['form'] ) )
			return;

		if ( ! array_key_exists( $_GET['form'], $this->valid_forms ) )
			return;

		if ( empty( $_POST ) && ! isset( $_POST['twinfield_form_nonce' ] ) )
			return;

		if ( ! wp_verify_nonce( $_POST['twinfield_form_nonce'], 'twinfield_form_builder' ) )
			return;

		try {

			$formClass = new \ReflectionClass( __NAMESPACE__ . '\\Form\\' . ucfirst( $_GET['form'] ) );
			$form = $formClass->newInstance();

			if ( $form->submitted() ) {
				echo $form->getSuccessMessage();
			} else {
				echo $form->getFailedMessage();
			}

		} catch( \Exception $e ) {
			var_dump($e);
		}
	}

}