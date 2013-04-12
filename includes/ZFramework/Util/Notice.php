<?php

namespace ZFramework\Util;
/**
 * Notice Class
 *
 * Generates notices for use in WordPress admin
 *
 * @package ZFramework
 * @subpackage Util
 * @author Leon Rowland <leon@rowland.nl>
 * @copyright (c) 2013, Leon Rowland
 * @version 0.0.1
 */
class Notice {

	/**
	 * Holds all notices
	 * @var array
	 */
	private $notices = array(
		'updated'	 => array(),
		'error'		 => array()
	);

	public function __construct() {
		add_action( 'admin_notices', array( $this, 'get' ) );
	}

	/**
	 * Adds an 'updated' message for this page load.
	 *
	 * @since 0.0.1
	 *
	 * @access public
	 * @param string $message
	 * @param int $priority
	 * @return void
	 */
	public function updated( $message, $priority = 10 ) {
		$this->insert_into( 'updated', $priority, array(
			'message' => $message
		) );
		
		return $this;
	}

	/**
	 * Adds an 'error' message for this page load
	 *
	 * @since 0.0.1
	 *
	 * @access public
	 * @param string $message
	 * @param int $priority
	 * @return void
	 */
	public function error( $message, $priority = 10 ) {
		$this->insert_into( 'error', $priority, array(
			'message' => $message
		) );
		
		return $this;
	}

	/**
	 * Adds the message to the private notices array
	 * with a priority order
	 *
	 * @since 0.0.1
	 *
	 * @access private
	 * @param string $type
	 * @param int $priority
	 * @param array $notice_array
	 * @return void
	 */
	private function insert_into( $type, $priority, $notice_array = array() ) {
		$this->notices[$type][$priority][] = $notice_array;
	}

	/**
	 * Hooked function to generate the notices.
	 *
	 * Will send content straight to browser
	 *
	 * @action admin_notices
	 *
	 * @since 0.0.1
	 * @return void
	 */
	public function get() {
		foreach ( $this->notices as $type => $priorities ) {
			foreach ( $priorities as $notices ) {
				foreach ( $notices as $notice ) {
					echo '<div class="' . $type . '">';
					echo '<p>' . $notice['message'] . '</p>';
					echo '</div>';
				}
			}
		}
	}
}