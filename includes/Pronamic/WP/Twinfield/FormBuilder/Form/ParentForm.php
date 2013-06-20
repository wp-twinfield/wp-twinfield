<?php

namespace Pronamic\WP\Twinfield\FormBuilder\Form;

use \Pronamic\Twinfield\Response\Response;

/**
 * Extend this class to make your own Form for the FormBuilder.
 * 
 * Those forms can be extended themselves to support
 * custom Form Builder implementations of each existing Child Form.
 * 
 * An example is to extend the Invoice form for WooCommerce, where you set 
 * more extra variables, to replace the contents of the Article Input, to
 * show all the products in a dropdown.
 * 
 * @package FormBuilder
 * @subpackage ParentForm
 * 
 * @author Leon Rowland <leon@rowland.nl>
 * @version 1.0
 */
abstract class ParentForm {
	
	/**
	 * Holds the full path location
	 * of the view file associated with
	 * the extended class.
	 * @var string
	 */
	private $view_file;
	
	/**
	 * Holds the response from a Factory request.
	 * @var \Pronamic\Twinfield\Response\Response
	 */
	private $response;

	/**
	 * Holds all the extra variables for the form.
	 * @var array
	 */
	private $extra_variables = array();
	
	public function set_view( $view_file ) {
		$this->view_file = $view_file;
	}
	
	/**
	 * Class to handle the submission of the form. From here
	 * you are should pass in the data to fill_class and the response
	 * back should be sent to this classes methods set_response.
	 * 
	 * @access public
	 * @return boolean
	 */
	abstract public function submit( $data = null );
	
	/**
	 * Should translate the passed in array data to 
	 * the Objects that relate to your extended Form.
	 * 
	 * It should return with the object that would be sent
	 * with the submit method above.
	 * 
	 * @access public
	 * @return stdClass
	 */
	abstract public function fill_class( array $data );

	/**
	 * Sets the response from the submission.  Is recommended to call this 
	 * method from the response inside the submit() method.
	 * 
	 * Typically the getResponse method call on the factory of your related 
	 * child class
	 * 
	 * @access public
	 * @param \Pronamic\Twinfield\Response\Response $response
	 * @return void
	 */
	public function set_response( Response $response ) {
		$this->response = $response;
	}

	/**
	 * Will return the response from the submission request 
	 * 
	 * @access public
	 * @return \Pronamic\Twinfield\Response\Response
	 */
	public function get_response() {
		return $this->response;
	}
	
	/**
	 * Sets extra variables for the rendered form builder
	 * form view.
	 * 
	 * @access public
	 * @param string $variable_key
	 * @param string $value
	 */
	public function set_extra_variables( $variable_key, $value ) {
		$this->extra_variables[$variable_key] = $value;
	}
	
	/**
	 * Extra variables set by the child form are retrieved
	 * from here
	 * 
	 * @access public
	 * @return array
	 */
	public function get_extra_variables() {
		return $this->extra_variables;
	}
	
	/**
	 * Displays the form and passes an instance of themself and
	 * and extra variables from the child class.
	 * 
	 * @access public
	 * @return void
	 */
	public function render( $data = array() ) {
		if ( ! empty( $this->view_file ) && file_exists( $this->view_file ) ) {
			
			// Gets the variables required
			$nonce		 = wp_nonce_field( 'twinfield_form_builder', 'twinfield_form_nonce', true, false );
			$object		 = $this->fill_class( $data );
			$form_extra	 = $this->get_extra_variables();
			
			include $this->view_file;
		}
	}
}