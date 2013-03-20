<?php

namespace Pronamic\Twinfield\Secure;

class Config {

	private static $credentials = array();

	public static function setCredentials( $username, $password, $organisation, $office ) {
		self::$credentials['user'] = $username;
		self::$credentials['password'] = $password;
		self::$credentials['organisation'] = $organisation;
		self::$credentials['office'] = $office;
	}

	public static function getCredentials() {
		return self::$credentials;
	}

	public static function getUsername() {
		return self::$credentials['user'];
	}

	public static function getPassword() {
		return self::$credentials['password'];
	}

	public static function getOrganisation() {
		return self::$credentials['organisation'];
	}

	public static function getOffice() {
		return self::$credentials['office'];
	}
}