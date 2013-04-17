<?php

namespace Pronamic\WP\Merge\Twinfield\Supports;

abstract class BaseSupport {
	abstract public function create_response();
	abstract public function automate();
	
	private $config;
	
	public function __construct( \Pronamic\Twinfield\Secure\Config $config ) {
		$this->config = $config;
	}
	
	public function getConfig() {
		return $this->config;
	}
	
	public function getLimit() {
		return filter_input( INPUT_GET, 'limit', FILTER_VALIDATE_INT );
	}
	
	public function getOffset() {
		return filter_input( INPUT_GET, 'offset', FILTER_VALIDATE_INT );
	}
	
	public function getNewField() {
		return filter_input( INPUT_GET, 'new_field', FILTER_SANITIZE_STRING );
	}
	
	public function getNewFieldValue() {
		return filter_input( INPUT_POST, 'new_field_value', FILTER_SANITIZE_STRING );
	}
	
	public function getCurrentField() {
		return filter_input( INPUT_GET, 'current_field', FILTER_SANITIZE_STRING );
	}
	
	public function update( $post_id = null, $meta_key = null, $meta_value = null ) {
		
		// Get new meta key and value and post id
		if ( ! $post_id )
			$post_id = filter_input( INPUT_POST, 'post_id', \FILTER_SANITIZE_NUMBER_INT );
		
		if ( ! $meta_key )
			$meta_key = $this->getNewField();
		
		if ( ! $meta_value )
			$meta_value = $this->getNewFieldValue();
		
		// Set the new keys value, assigned to the linked post id
		update_post_meta( $post_id, $meta_key, $meta_value );
	}
}