<?php

namespace Pronamic\WP\FormBuilder;
/**
 * FormBuilder Class
 *
 * Used to create forms, and listen for inputs.  Will
 * load the right classes depending on chosen form
 *
 * @since 0.0.1
 *
 * @package Pronamic\WP
 * @subpackage FormBuilder
 * @author Leon Rowland <leon@rowland.nl>
 * @copyright (c) 2013, Leon Rowland
 * @version 0.0.1
 */
use \ZFramework\Base\View;

class FormBuilder {

	/**
	 * Holds the valid forms, and their
	 * associated Form Class
	 *
	 * @access private
	 * @var array
	 */
	private $valid_forms = array(
		'invoice' => "Pronamic\WP\FormBuilder\Form\Invoice",
		'customer' => "Pronamic\WP\FormBuilder\Form\Customer"
	);

	/**
	 * Holds the valid forms, form view file
	 *
	 * @access private
	 * @var array
	 */
	private $valid_forms_views = array(
		'invoice' => 'create_form_invoice',
		'customer' => 'create_form_customer'
	);

	/**
	 * Adds a hook for the admin_init to listen for form submissions
	 */
	public function __construct() {
		add_action( 'admin_init', array( $this, 'listen' ) );
	}

	/**
	 * Returns all valid forms that have been made and
	 * verified to work.
	 *
	 * @since 0.0.1
	 *
	 * @access public
	 * @return array All valid forms
	 */
	public function get_valid_forms() {
		return array_keys( $this->valid_forms );
	}

	/**
	 * Returns the view file name for this form type
	 *
	 * @since 0.0.1
	 *
	 * @access public
	 * @param string $type The key name for the form
	 * @return boolean
	 */
	public function get_form_view_file_name( $type ) {
		if ( array_key_exists( $type, $this->valid_forms_views ) )
			return $this->valid_forms_views[$type];
		else
			return false;
	}

	/**
	 * Will create a form based off the passed type
	 *
	 * @since 0.0.1
	 *
	 * @access public
	 * @param string $type The name of the form to create
	 * @param boolean $return Whether to return the view or send straight to browser
	 * @return string The View of the chosen form
	 */
	public function create_form( $type = null, $return = false ) {
		// Check type has been supplied or return
		if ( ! $type && ! isset( $_GET['twinfield_form'] ) )
			return;

		if ( ! $type )
			$type = $_GET['twinfield_form'];

		// Check the type is a valid form or return
		if ( ! array_key_exists( $type, $this->valid_forms ) )
			return;

		// Get the class of the chosen type
		$form = $this->get_class_from_type( $type );

		// Generate a nonce
		$nonce = wp_nonce_field( 'twinfield_form_builder', 'twinfield_form_nonce', true, false );

		// Get the view file name
		$viewFile = $this->get_form_view_file_name( $type );

		// Prepare the view
		$view = new View( dirname( \Twinfield::$file ) . '/views/Pronamic/WP/FormBuilder' );
		$view
			->setVariable( 'nonce', $nonce )
			->setVariable( $type, $form->fill_class( $_POST ) )
			->setVariable( 'form_extra', $form->extra_variables() )
			->setView( $viewFile );

		// Determine if it should return or show the view
		if ( $return ) {
			return $view->retrieve();
		} else {
			$view->render();
		}
	}

	public function listen() {
		if ( ! isset( $_GET['twinfield_form'] ) )
			return;

		$type = $_GET['twinfield_form'];

		if ( ! array_key_exists( $type, $this->valid_forms ) )
			return;

		if ( empty( $_POST ) || ! isset( $_POST['twinfield_form_nonce' ] ) )
			return;

		if ( ! wp_verify_nonce( $_POST['twinfield_form_nonce'], 'twinfield_form_builder' ) )
			return;

		$class = $this->get_class_from_type( $type );

		$notice = new \ZFramework\Util\Notice();
		if ( true === $class->submit() ) {
			$notice->updated('Successful');
		} else {
			foreach ( $class->get_response()->getErrorMessages() as $error ) {
				$notice->error($error);
			}
		}
	}

	private function get_class_from_type( $type ) {
		try {

			$reflectionClass = new \ReflectionClass( $this->valid_forms[$type] );
			return $reflectionClass->newInstance();

		} catch( \Exception $e ) {
			return false;
		}
	}



}