<?php

/**
 * FormBuilder Class
 *
 * Used to create forms, and listen for inputs. 
 * 
 * Uses the FormBuilderFactory
 *
 * @since 0.0.1
 *
 * @package Pronamic\WP\Twinfield
 * @subpackage FormBuilder
 * @author Leon Rowland <leon@rowland.nl>
 * @copyright (c) 2013, Leon Rowland
 * @version 0.0.1
 */

namespace Pronamic\WP\Twinfield\FormBuilder;
class FormBuilder {

	/**
	 * Adds a hook for the admin_init to listen for form submissions
	 */
	public function __construct() {
		add_action( 'admin_init', array( $this, 'listen' ) );
	}
	
	/**
	 * Shows the form from a registered form in the
	 * FormBuilderFactory.  Pass in the unique name
	 * for the form.
	 * 
	 * @access public
	 * @param string $twinfield_form
	 * @return void
	 */
	public function show_form( $twinfield_form ) {
		// Check the twinfield form has been registered
		if ( $form = FormBuilderFactory::get_form( $twinfield_form ) ) {
						
			// Render the view
			$form->render( $_POST );
		}
	}
	
	/**
	 * Listens to every admin page request to determine if a form
	 * from the FormBuilder has been submitted.
	 * 
	 * @todo decouple the relation with INPUT_GET and a set key
	 * 
	 * @access public
	 * @return void
	 */
	public function listen() {
		if ( ! filter_has_var( INPUT_GET, 'twinfield-form' ) )
			return;
		
		if ( empty( $_POST ) || ! isset( $_POST['twinfield_form_nonce' ] ) )
			return;

		if ( ! wp_verify_nonce( $_POST['twinfield_form_nonce'], 'twinfield_form_builder' ) )
			return;
		
		do_action( 'wp_twinfield_formbuilder_load_forms' );
		
		// Get the form name from the INPUT_GET input
		// @todo should be decoupled
		$twinfield_form = filter_input( INPUT_GET, 'twinfield-form', FILTER_SANITIZE_STRING );
		
		// Get the form instance
		$form = FormBuilderFactory::get_form( $twinfield_form );
		
		// Attempt the submit of this form
		$success = $form->submit( $_POST );
		
		// Get the notice class
		$notice = new \ZFramework\Util\Notice();
		
		// Determine the success response of the submission
		if ( true === $success ) {
			
			$notice->updated( 'Successful' );
			
		} else {
			
			foreach ( $form->get_response()->getErrorMessages() as $error ) {
				$notice->error( $error );
			}
		}
	}
}