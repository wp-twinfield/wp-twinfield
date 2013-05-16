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
	
	public function cleaned_data( $input ) {
		// Determine if a constant
		if ( is_int( $input ) ) {
			return $this->input_clean( $input );
		} elseif( is_array( $input ) ) {
			return $this->array_clean( $input );
		}
	}
	
	private function input_clean( $input_type ) {
		$cleaned_data = array();
		
		$cleaned_data['id'] = filter_input( $input_type, 'id', FILTER_VALIDATE_INT );
		$cleaned_data['name'] = filter_input( $input_type, 'name', FILTER_SANITIZE_STRING );
		$cleaned_data['type'] = filter_input( $input_type, 'type', FILTER_SANITIZE_STRING );
		$cleaned_data['website'] = filter_input( $input_type, 'website', FILTER_VALIDATE_URL );
		$cleaned_data['duedays'] = filter_input( $input_type, 'duedays', FILTER_VALIDATE_INT );
		$cleaned_data['ebilling'] = filter_input( $input_type, 'ebilling', FILTER_VALIDATE_BOOLEAN );
		$cleaned_data['ebillmail'] = filter_input( $input_type, 'ebillmail', FILTER_VALIDATE_EMAIL );
		$cleaned_data['vatcode'] = filter_input( $input_type, 'vatcode', FILTER_SANITIZE_STRING );
		
		if ( 0 === $input_type ) {
			$addresses = $_POST['addresses'];
		} else {
			$addresses = $_GET['addresses'];
		}
		
		$cleaned_data['addresses'] = array();
		
		foreach ( $addresses as $address ) {
			$clean_address = array();
			$clean_address['default'] = filter_var( $address['default'], FILTER_VALIDATE_BOOLEAN );
			$clean_address['type'] = filter_var( $address['type'], FILTER_SANITIZE_STRING );
			$clean_address['name'] = filter_var( $address['name'], FILTER_SANITIZE_STRING );
			$clean_address['field1'] = filter_var( $address['field1'], FILTER_SANITIZE_STRING );
			$clean_address['field2'] = filter_var( $address['field2'], FILTER_SANITIZE_STRING );
			$clean_address['field3'] = filter_var( $address['field3'], FILTER_SANITIZE_STRING );
			$clean_address['field5'] = filter_var( $address['field5'], FILTER_SANITIZE_STRING );
			$clean_address['postcode'] = filter_var( $address['postcode'], FILTER_SANITIZE_STRING );
			$clean_address['city'] = filter_var( $address['city'], FILTER_SANITIZE_STRING );
			$clean_address['country'] = filter_var( $address['country'], FILTER_SANITIZE_STRING );
			$clean_address['email'] = filter_var( $address['email'], FILTER_SANITIZE_EMAIL );
			
			$cleaned_data['addresses'][] = stripslashes_deep( $clean_address );
		}
		
		return $cleaned_data;
	}
	
	private function array_clean( $data = array() ) {
		$cleaned_data = array();
		
		$cleaned_data['id'] = filter_input( $input_type, 'id', FILTER_VALIDATE_INT );
		$cleaned_data['name'] = filter_input( $input_type, 'name', FILTER_SANITIZE_STRING );
		$cleaned_data['type'] = filter_input( $input_type, 'type', FILTER_SANITIZE_STRING );
		$cleaned_data['website'] = filter_input( $input_type, 'website', FILTER_VALIDATE_URL );
		$cleaned_data['duedays'] = filter_input( $input_type, 'duedays', FILTER_VALIDATE_INT );
		$cleaned_data['ebilling'] = filter_input( $input_type, 'ebilling', FILTER_VALIDATE_BOOLEAN );
		$cleaned_data['ebillmail'] = filter_input( $input_type, 'ebillmail', FILTER_VALIDATE_EMAIL );
		$cleaned_data['vatcode'] = filter_input( $input_type, 'vatcode', FILTER_SANITIZE_STRING );
		
		if ( 0 === $input_type ) {
			$addresses = $_POST['addresses'];
		} else {
			$addresses = $_GET['addresses'];
		}
		
		$cleaned_data['addresses'] = array();
		
		foreach ( $addresses as $address ) {
			$clean_address = array();
			$clean_address['default'] = filter_var( $address['default'], FILTER_VALIDATE_BOOLEAN );
			$clean_address['type'] = filter_var( $address['type'], FILTER_SANITIZE_STRING );
			$clean_address['name'] = filter_var( $address['name'], FILTER_SANITIZE_STRING );
			$clean_address['field1'] = filter_var( $address['field1'], FILTER_SANITIZE_STRING );
			$clean_address['field2'] = filter_var( $address['field2'], FILTER_SANITIZE_STRING );
			$clean_address['field3'] = filter_var( $address['field3'], FILTER_SANITIZE_STRING );
			$clean_address['field5'] = filter_var( $address['field5'], FILTER_SANITIZE_STRING );
			$clean_address['postcode'] = filter_var( $address['postcode'], FILTER_SANITIZE_STRING );
			$clean_address['city'] = filter_var( $address['city'], FILTER_SANITIZE_STRING );
			$clean_address['country'] = filter_var( $address['country'], FILTER_SANITIZE_STRING );
			$clean_address['email'] = filter_var( $address['email'], FILTER_SANITIZE_EMAIL );
			
			$cleaned_data['addresses'][] = stripslashes_deep( $clean_address );
		}
		
		return $cleaned_data;
	}
}