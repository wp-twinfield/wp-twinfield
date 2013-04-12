<?php

namespace Pronamic\WP\Merge;

class Merge {

	public function __construct() {
		add_action( 'admin_init', array( $this, 'listener' ) );
		add_action( 'admin_init', array( $this, 'automate' ) );
	}

	public function listener() {
		if ( ! isset( $_POST[ 'action' ] ) || ! isset( $_GET[ 'page' ] ) )
			return;
		
		if ( 'twinfield-merger' !== $_GET['page'] )
			return;
		
		if ( 'merger_tool' !== $_POST[ 'action' ] )
			return;
		
		// MergerFinder
		$finder = new MergeFinder();
		
		$found = $finder->get_class_from_support( $_GET['twinfield-table'] );
		$found->update();
		
	}
	
	public function automate() {
		if ( ! isset( $_POST[ 'action' ] ) || ! isset( $_GET[ 'page' ] ) )
			return;
		
		if ( 'twinfield-merger' !== $_GET['page'] )
			return;
		
		if ( 'merger_automate' !== $_POST[ 'action' ] )
			return;
		
		// MergerFinder
		$finder = new MergeFinder();
		
		$found = $finder->get_class_from_support( $_GET['twinfield-table'] );
		$found->automate();
	}

}