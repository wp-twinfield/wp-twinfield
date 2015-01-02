<?php

function get_twinfield_invoice_link( $id ) {
	global $wp_rewrite;

	$link = false;

	if ( $wp_rewrite->using_permalinks() ) {
		$slug = get_option( 'twinfield_invoice_slug' );

		if ( empty( $slug ) ) {
			$slug = _x( 'invoice', 'Invoice slug for frontend', 'twinfield' );
		}

		$link = site_url( $slug . '/' . $id . '/' );
	} else {
		$link = add_query_arg( 'twinfield_sales_invoice_id', $id, home_url( '/' ) );
	}

	return $link;
}

function get_twinfield_customer_link( $id ) {
	global $wp_rewrite;

	$link = false;

	if ( $wp_rewrite->using_permalinks() ) {
		$slug = get_option( 'twinfield_customer_slug' );

		if ( empty( $slug ) ) {
			$slug = _x( 'customer', 'Customer slug for frontend', 'twinfield' );
		}

		$link = home_url( $slug . '/' . $id . '/' );
	} else {
		$link = add_query_arg( 'twinfield_debtor_id', $id, home_url( '/' ) );
	}

	return $link;
}
