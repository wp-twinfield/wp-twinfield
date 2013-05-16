<?php

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


function twinfield_get_form_action( $type ) {
	return admin_url( 'admin.php?page=twinfield-form-builder&twinfield-form=' . $type );
}

function twinfield_get_merger_table_action( $support ) {
	return admin_url( 'admin.php?page=twinfield-merger&twinfield-table=' . $support );
}

function twinfield_price( $price ) {
	$return = '';

	$return .= '&euro;';
	$return .= '&nbsp;';

	$return .= number_format( $price, 2, ',', '.' );

	return $return;
}
