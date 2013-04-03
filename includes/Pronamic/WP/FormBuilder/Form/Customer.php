<?php

namespace Pronamic\WP\FormBuilder\Form;

use \Pronamic\Twinfield\Customer\Customer as TwinfieldCustomer;
use \Pronamic\Twinfield\Customer\CustomerAddress as TwinfieldCustomerAddress;
use \Pronamic\Twinfield\Customer\CustomerFactory as TwinfieldCustomerFactory;

class Customer extends ParentForm {
	public function submit() {
		global $twinfield_config;

		$customer_factory = new TwinfieldCustomerFactory( $twinfield_config );

		if ( $customer_factory->send( $this->fill_class() ) ) {
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

	public function fill_class( $data = null ) {
		if ( ! $data )
			$data = $_POST;

		$defaultData = array(
			'id'		 => '',
			'name'		 => '',
			'type'		 => 'DEB',
			'website'	 => '',
			'duedays'	 => '',
			'ebilling'	 => '',
			'ebillmail'	 => '',
			'vatcode'	 => ''
		);

		$data = array_merge( $defaultData, $data );

		$customer = new TwinfieldCustomer();
		$customer
				->setID( filter_var( $data['id'], FILTER_VALIDATE_INT ) )
				->setName( filter_var( $data['name'], FILTER_SANITIZE_STRING ) )
				->setType( filter_var( $data['type'], FILTER_SANITIZE_STRING ) )
				->setWebsite( filter_var( $data['website'], FILTER_VALIDATE_URL ) )
				->setDueDays( filter_var( $data['duedays'], FILTER_VALIDATE_INT ) )
				->setEbilling( filter_var( $data['ebilling'], FILTER_VALIDATE_BOOLEAN ) )
				->setEBillMail( filter_var( $data['ebillmail'], FILTER_VALIDATE_EMAIL ) )
				->setVatCode( filter_var( $data['vatcode'], FILTER_SANITIZE_STRING ) );

		if ( ! empty( $data['addresses'] ) ) {

			$defaultAddressData = array(
				'default'	 => true,
				'type'		 => '',
				'field1'	 => '',
				'field2'	 => '',
				'field3'	 => '',
				'field5'	 => '',
				'postcode'	 => '',
				'city'		 => '',
				'country'	 => '',
				'email'		 => ''
			);

			foreach ( $data['addresses'] as $address ) {
				$address = array_merge( $defaultAddressData, $address );

				$temp_address = new TwinfieldCustomerAddress();
				$temp_address
						->setDefault($address['default'])
						->setType($address['type'])
						->setField1($address['field1'])
						->setField2($address['field2'])
						->setField3($address['field3'])
						->setField5($address['field5'])
						->setPostcode($address['postcode'])
						->setCity($address['city'])
						->setCountry($address['country'])
						->setEmail($address['email']);

				$customer->addAddress($temp_address);

			}
		}

		return $customer;

	}
}