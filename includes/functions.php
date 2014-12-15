<?php

function twinfield_settings_section( $name ) {
	$filename = PRONAMIC_TWINFIELD_FOLDER . 'views/' . $name . '.php';

	if ( is_readable( $filename ) ) {
		include $filename;
	}
}

function twinfield_get_offices() {

}

function twinfield_get_debitors() {

}

function twinfield_get_sales_invoice( $id ) {

}

function twinfield_get_transaction( $number, $code, $office ) {
	/*
	<?xml version="1.0"?>
	<read>
		<type>transaction</type>
		<office>11024</office>
		<code>VRK</code>
		<number>201100001</number>
	</read>
	*/
}

function twinfield_get_debtor( $id ) {

}

function twinfield_admin_view_customer_link( $customer_id = false ) {
	$query_args = array(
		'page'                  => 'twinfield_customers',
		'twinfield_customer_id' => $customer_id,
	);

	return add_query_arg( $query_args, admin_url( 'admin.php' ) );
}

function twinfield_admin_view_invoice_link( $invoice_id = false ) {
	$query_args = array(
		'page'                 => 'twinfield_invoices',
		'twinfield_invoice_id' => $invoice_id,
	);

	return add_query_arg( $query_args, admin_url( 'admin.php' ) );
}

function twinfield_price( $price ) {
	$return = '';

	$return .= '&euro;';
	$return .= '&nbsp;';

	$return .= number_format( $price, 2, ',', '.' );

	return $return;
}
