<?php

class TestFormBuilderFactory extends WP_UnitTestCase {
	
	public $invoice;
	
	public function setUp() {
		$this->invoice = new \Pronamic\WP\Twinfield\FormBuilder\Form\Invoice();
	}
	
	public function testFactoryStoresRegisteredForms() {
		\Pronamic\WP\Twinfield\FormBuilder\FormBuilderFactory::register_form( 'invoice', $this->invoice );
		$this->assertArrayHasKey( 'invoice', \Pronamic\WP\Twinfield\FormBuilder\FormBuilderFactory::$forms );
	}
	
	public function testFactoryValidFormReturnsTrueIfThere() {
		$this->assertTrue( \Pronamic\WP\Twinfield\FormBuilder\FormBuilderFactory::is_valid_form( 'invoice' ) );
	}
	
	public function testFactoryValidFormReturnsFalseIfMissing() {
		$this->assertFalse( \Pronamic\WP\Twinfield\FormBuilder\FormBuilderFactory::is_valid_form( 'unknown_form' ) );
	}
	
	public function testFactoryReturnsFalseIfGetFormIsMissing() {
		$this->assertFalse( \Pronamic\WP\Twinfield\FormBuilder\FormBuilderFactory::get_form( 'unknown_form' ) );
	}
	
	public function testFactoryReturnsCorrectForm() {
		$invoice_form = \Pronamic\WP\Twinfield\FormBuilder\FormBuilderFactory::get_form('invoice');
		$this->assertEquals( $invoice_form, $this->invoice );
	}
	
	public function testAllFormNamesOfRegisteredFormsWillReturnAsAnArray() {
		$this->assertTrue( is_array( \Pronamic\WP\Twinfield\FormBuilder\FormBuilderFactory::get_all_form_names() ) );
	}
	
	public function testAllFormNamesHasRegisteredForms() {
		$this->assertTrue( in_array( 'invoice', \Pronamic\WP\Twinfield\FormBuilder\FormBuilderFactory::get_all_form_names() ) );
	}
}