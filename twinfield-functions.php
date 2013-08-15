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

function wp_twinfield_admin_query_nav( $ignore_trigger = '' ) {
    $selected_tab = filter_input( INPUT_GET, 'tab', FILTER_SANITIZE_STRING );
    
    $tabs = array(
        'customer' => array(
            'title' => __( 'Customer', 'twinfield' ),
            'link' => twinfield_admin_view_customer_link()
        ),
        'invoice' => array(
            'title' =>  __( 'Invoice', 'twinfield' ),
            'link' => twinfield_admin_view_invoice_link()
        )
    );
    
    if ( ! filter_has_var( INPUT_GET, $ignore_trigger ) ) : ?>
    
    <h2 class="nav-tab-wrapper">
        <?php foreach ( $tabs as $tab => $att ) : ?>
        <a class="nav-tab <?php if ( $tab === $selected_tab ): ?>nav-tab-active<?php endif; ?>" href="<?php echo $att['link']; ?>"><?php echo $att['title']; ?></a>
        <?php endforeach; ?>
    </h2>

    <?php endif;
}

function twinfield_admin_view_customer_link( $customer_id = null ) {
    $query_args = array(
        'page' => 'twinfield-query',
        'tab' => 'customer'
    );
    
    if ( null !== $customer_id )
        $query_args['twinfield_customer_id'] = $customer_id;
    
	return add_query_arg( $query_args, admin_url( 'admin.php' ) );
}

function twinfield_admin_view_invoice_link( $invoice_id = false ) {
	$query_args = array(
		'page'                 => 'twinfield_invoices',
		'twinfield_invoice_id' => $invoice_id
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
