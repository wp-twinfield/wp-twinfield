<?php

namespace Pronamic\WP\Twinfield\FormBuilder\Form;

use \Pronamic\Twinfield\Customer\Customer as TwinfieldCustomer;
use \Pronamic\Twinfield\Customer\CustomerAddress as TwinfieldCustomerAddress;
use \Pronamic\Twinfield\Customer\CustomerFactory as TwinfieldCustomerFactory;

class Customer extends ParentForm {
	public $defaultCustomerData = array(
		'id'		 => '',
		'name'		 => '',
		'type'		 => 'DEB',
		'website'	 => '',
		'duedays'	 => '',
		'ebilling'	 => '',
		'ebillmail'	 => '',
		'vatcode'	 => ''
	);
	
	public $defaultCustomerAddressData = array(
		'default'	 => true,
		'type'		 => '',
		'name'		 => '',
		'field1'	 => '',
		'field2'	 => '',
		'field3'	 => '',
		'field5'	 => '',
		'postcode'	 => '',
		'city'		 => '',
		'country'	 => '',
		'email'		 => ''
	);
	
	public function submit( $data = null ) {
		global $twinfield_config;

		$customer_factory = new TwinfieldCustomerFactory( $twinfield_config );

		if ( $customer_factory->send( $this->fill_class( $data ) ) ) {
			$this->set_response($customer_factory->getResponse());
			return true;
		} else {
			$this->set_response($customer_factory->getResponse());
			return false;
		}
	}

	public function extra_variables() {
		// Latest customer id
		global $twinfield_config;

		$customer_factory = new TwinfieldCustomerFactory( $twinfield_config );

		$customers = $customer_factory->listAll();

		if ( empty( $customers ) ) return array();

		// Get array keys
		$customer_keys = array_keys($customers);

		asort($customer_keys);
		$customer_keys = array_reverse($customer_keys);

		$latest_customer_id = $customer_keys[0];

		return array(
			'latest_customer_id' => ++$latest_customer_id
		);
	}

	public function fill_class( $data = array() ) {
		
		$data = array_merge( $this->defaultCustomerData , $data );
		
		$data = stripslashes_deep( $data );

		$customer = new TwinfieldCustomer();
		$customer
				->setID( $data['id'] )
				->setName( $data['name'] )
				->setType( $data['type'] )
				->setWebsite( $data['website'] )
				->setDueDays( $data['duedays'] )
				->setEbilling( $data['ebilling'] )
				->setEBillMail( $data['ebillmail'] )
				->setVatCode( $data['vatcode'] );

		if ( ! empty( $data['addresses'] ) ) {

			foreach ( $data['addresses'] as $address ) {
				$address = array_merge( $this->defaultCustomerAddressData, $address );

				$temp_address = new TwinfieldCustomerAddress();
				$temp_address
						->setDefault( $address['default'] )
						->setType( $address['type'] )
						->setName( $address['name'] )
						->setField1( $address['field1'] )
						->setField2( $address['field2'] )
						->setField3( $address['field3'] )
						->setField5( $address['field5'] )
						->setPostcode( $address['postcode'] )
						->setCity( $address['city'] )
						->setCountry( $address['country'] )
						->setEmail( $address['email'] );
				
				$customer->addAddress( $temp_address );

			}
		}

		return $customer;

	}
}