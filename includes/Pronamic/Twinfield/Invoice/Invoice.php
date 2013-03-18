<?php

namespace Pronamic\Twinfield\Invoice;

class Invoice {
	private $customer;

	private $invoiceType;

	private $orders;

	public function addOrder(Order $order) {
		$this->orders[$order->getID()] = $order;
		return $this;
	}

	public function removeOrder( $uid ) {
		if ( array_key_exists( $uid, $this->orders ) ) {
			unset( $this->orders[$uid] );
			return true;
		} else {
			return false;
		}
	}

	public function getOrders() {
		return $this->orders;
	}

	public function getCustomer() {
		return $this->customer;
	}

	public function setCustomer( \Pronamic\Twinfield\Customer\Customer $customer ) {
		$this->customer = $customer;
		return $this;
	}

	public function getInvoiceType() {
		return $this->invoiceType;
	}

	public function setInvoiceType( $invoiceType ) {
		$this->invoiceType = $invoiceType;
		return $this;
	}
}