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

function twinfield_admin_notice( $message ) {
	add_action( 'admin_notices', function() {
		?>
		<div class="error">
			<p><?php echo $message; ?></p>
		</div>
		<?php

	});
}