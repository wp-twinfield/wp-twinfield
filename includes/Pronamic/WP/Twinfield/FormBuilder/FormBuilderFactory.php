<?php

namespace Pronamic\WP\Twinfield\FormBuilder;

/**
 * Static class to generate, register and return the registered
 * FormBuilder forms.
 * 
 * Is the recommended way to extend the form builder.
 * 
 * @author Leon Rowland <leon@rowland.nl>
 */
class FormBuilderFactory {
	
	/**
	 * Holds all registered form
	 * instances
	 * 
	 * @var array
	 */
	public static $forms = array();
	
	/**
	 * Checks the passed in form name has been registered
	 * 
	 * @access public
	 * @param string $form_name
	 * @return boolean
	 */
	public static function is_valid_form( $form_name ) {
		return ( array_key_exists( $form_name, self::$forms ) );
	}
	
	/**
	 * Registers an instance of the form with a unique name
	 * that is used in the form builder.
	 * 
	 * Make an instance of your form and pass as the second param.
	 * NOT THE NAME!
	 * 
	 * @access public
	 * @param string $form_name
	 * @param FormBuilder $form_instance
	 */
	public static function register_form( $form_name, Form\ParentForm $form_instance ) {
		self::$forms[$form_name] = $form_instance;
	}
	
	/**
	 * Returns the registered form instance
	 * 
	 * @access public
	 * @param string $form_name
	 * @return boolean|FormBuilder
	 */
	public static function get_form( $form_name ) {
		if ( self::is_valid_form( $form_name ) ) {
			return self::$forms[$form_name]; 
		} else {
			return false;
		}
	}
	
	/**
	 * Returns all the form unique names that have been registered
	 * 
	 * @access public
	 * @return array
	 */
	public static function get_all_form_names() {
		return array_keys( self::$forms );
	}
	
}