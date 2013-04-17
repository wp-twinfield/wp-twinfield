<?php

namespace Pronamic\WP\Twinfield\Merge;

/**
 * Merge Class
 * 
 * Used to create merge tables and listen for
 * inputs.  Will load the right 'Supports' class
 * from the chosen table.
 * 
 * @since 0.0.1
 * 
 * @package Pronamic\WP\Twinfield
 * @subpackage Merge
 * @author Leon Rowland <leon@rowland.nl>
 * @copyright (c) 2013, Leon Rowland
 * @version 0.0.1
 */

class MergeFinder {

	/**
	 * Holds the valid supports and
	 * their class namespaced name
	 * 
	 * @access private
	 * @var array
	 */
	private $valid_supports = array(
		'customer' => "Pronamic\WP\Twinfield\Merge\Supports\Customer"
	);

	/**
	 * Holds the valid supports view file location
	 * 
	 * @access private
	 * @var array
	 */
	private $valid_supports_views = array(
		'customer' => 'merge_customer'
	);

	/**
	 * Returns all valid supports that have been made.
	 * 
	 * @since 0.0.1
	 * 
	 * @access public
	 * @return array
	 */
	public function get_valid_supports() {
		return array_keys( $this->valid_supports );
	}

	/**
	 * Returns the view name for the chosen support.
	 * 
	 * @since 0.0.1
	 * 
	 * @access public
	 * @param string $support
	 * @return boolean OR string
	 */
	public function get_support_view_file_name( $support ) {
		if ( array_key_exists( $support, $this->valid_supports_views ) )
			return $this->valid_supports_views[ $support ];
		else
			return false;
	}
	
	public function create_response( $support = null, $return = false ) {
		if ( ! $support && ! isset( $_GET[ 'twinfield-table' ] ) )
			return;

		if ( ! $support )
			$support = $_GET[ 'twinfield-table' ];

		// Check the support in the query string/passed if valid
		if ( ! array_key_exists( $support, $this->valid_supports_views ) )
			return;

		// Get the actual class object
		$table = $this->get_class_from_support( $support );
		$table->create_response();
	}

	/**
	 * Returns the support types namespaced class name,
	 * if it exists
	 * 
	 * @since 0.0.1
	 * 
	 * @access private
	 * @param string $support
	 * @return boolean OR string
	 */
	public function get_class_from_support( $support ) {
		try {
			$reflection_class = new \ReflectionClass( $this->valid_supports[ $support ] );
			
			global $twinfield_config;
			
			return $reflection_class->newInstance( $twinfield_config );
		} catch ( \Exception $e ) {
			return false;
		}
	}

}