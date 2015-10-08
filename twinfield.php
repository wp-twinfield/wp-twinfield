<?php
/*
Plugin Name: Twinfield
Plugin URI: http://www.pronamic.eu/plugins/twinfield/
Description: A base plugin to make the connection with the Twinfield administration software.

Author: Pronamic
Author URI: http://www.pronamic.eu/

Version: 1.1.0
Requires at least: 3.0

Text Domain: twinfield
Domain Path: /languages/

License: GPL

GitHub URI: https://github.com/pronamic/wp-twinfield
*/

/**
 * Composer autoload.
 */
$autoload_file = plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';

if ( is_readable( $autoload_file ) ) {
	require_once $autoload_file;
}

/**
 * Plugin bootstrap.
 */
global $twinfield_plugin;

$twinfield_plugin = new Pronamic\WP\Twinfield\Plugin\Plugin( __FILE__ );
