<?php

class TestInvoiceForm extends WP_UnitTestCase {

	public $invoice;
	public $test_data;

	public function setUp() {
		$this->invoice	 = new \Pronamic\WP\Twinfield\FormBuilder\Form\Invoice();
		$this->test_data = array(
			'customerID'	 => '1002',
			'invoiceType'	 => 'FACTUUR',
			'lines'			 => array(
				array(
					'active'		 => true,
					'article'		 => 1000,
					'subarticle'	 => 102,
					'units'			 => 10,
					'unitspriceexcl' => 9,
					'vatcode'		 => 'VH'
				)
			)
		);
	}

	public function testFormExtendsParentForm() {
		$this->assertInstanceOf( '\Pronamic\WP\Twinfield\FormBuilder\Form\ParentForm', $this->invoice );
	}

	public function testFillClassReturnsEmptyClassWithNoData() {
		$this->assertInstanceOf( '\Pronamic\Twinfield\Invoice\Invoice', $this->invoice->fill_class( array() ) );
	}

	public function testFillClassSetsPropertiesCorrectly() {
		$this->invoice_class = $this->invoice->fill_class( $this->test_data );
		$lines = $this->invoice_class->getLines();
		$line_keys = array_keys( $lines );
		$key = $line_keys[0];
		
		
		$this->assertEquals( $this->invoice_class->getCustomer()->getID(), $this->test_data['customerID'] );
		$this->assertEquals( $this->invoice_class->getInvoiceType(), $this->test_data['invoiceType'] );
		
		$this->assertTrue( count( $lines ) === 1 );
		
		$this->assertEquals( $lines[$key]->getArticle(), $this->test_data['lines'][0]['article'] );
		$this->assertEquals( $lines[$key]->getSubArticle(), $this->test_data['lines'][0]['subarticle']);
		$this->assertEquals( $lines[$key]->getUnits(), $this->test_data['lines'][0]['units']);
		$this->assertEquals( $lines[$key]->getUnitsPriceExcl(), $this->test_data['lines'][0]['unitspriceexcl']);
		$this->assertEquals( $lines[$key]->getVatCode(), $this->test_data['lines'][0]['vatcode'] );
		
	}

}