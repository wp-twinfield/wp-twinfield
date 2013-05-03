<?php

namespace Pronamic\WP\Twinfield\FormBuilder\Form;

use \Pronamic\Twinfield\Customer\Customer as TwinfieldCustomer;
use \Pronamic\Twinfield\Customer\CustomerAddress as TwinfieldCustomerAddress;
use \Pronamic\Twinfield\Customer\CustomerFactory as TwinfieldCustomerFactory;

class Customer extends ParentForm {
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

			foreach ( $data['addresses'] as $address ) {
				$address = array_merge( $defaultAddressData, $address );

				$temp_address = new TwinfieldCustomerAddress();
				$temp_address
						->setDefault( filter_var( $address['default'], FILTER_VALIDATE_BOOLEAN ) )
						->setType( filter_var( $address['type'], FILTER_SANITIZE_STRING ) )
						->setName( filter_var( $address['name'], FILTER_SANITIZE_STRING ) )
						->setField1( filter_var( $address['field1'], FILTER_SANITIZE_STRING ) )
						->setField2( filter_var( $address['field2'], FILTER_SANITIZE_STRING ) )
						->setField3( filter_var( $address['field3'], FILTER_SANITIZE_STRING ) )
						->setField5( filter_var( $address['field5'], FILTER_SANITIZE_STRING ) )
						->setPostcode( filter_var( $address['postcode'], FILTER_SANITIZE_STRING ) )
						->setCity( filter_var( $address['city'], FILTER_SANITIZE_STRING ) )
						->setCountry( filter_var( $address['country'], FILTER_SANITIZE_STRING ) )
						->setEmail( filter_var( $address['email'], FILTER_SANITIZE_STRING ) );

				$customer->addAddress( $temp_address );

			}
		}

		return $customer;

	}
}