<table class="form-table">
	<tr>
		<th scope="row">
			<label for="twinfield_customer_id"><?php esc_html_e( 'Customer ID', 'twinfield' ); ?></label>
		</th>
		<td>
			<input type="text" id="twinfield_customer_id" name="twinfield_customer_id" value="<?php echo esc_attr( $twinfield_customer_id ); ?>" />

			<?php if ( empty( $twinfield_customer_id ) ) : ?>

				<?php

				submit_button(
					__( 'Create Customer', 'twinfield' ),
					'secondary',
					'twinfield_create_customer',
					false
				);

				?>

			<?php else : ?>

				<a class="button" target="_blank" href="<?php echo esc_attr( twinfield_admin_view_customer_link( $twinfield_customer_id ) ); ?>"><?php esc_html_e( 'View Customer', 'twinfield' ); ?></a>

			<?php endif; ?>
		</td>
	</tr>
</table>

<?php

$response = get_post_meta( $post->ID, '_twinfield_customer_response_xml', true );

if ( $response ) {
	global $twinfield_plugin;

	echo '<pre>';
	echo esc_html( $response );
	echo '</pre>';

	$xml = new DOMDocument();
	$xml->loadXML( $response );

	$xsl = new DOMDocument();
	$xsl->load( plugin_dir_path( $twinfield_plugin->file ) . '/admin/twinfield-customer.xsl' );

	$proc = new XSLTProcessor();
	$proc->importStyleSheet( $xsl );

	echo $proc->transformToXML( $xml ); // xss ok
}

?>

<?php

// @codingStandardsIgnoreStart
if ( filter_input( INPUT_GET, 'debug', FILTER_VALIDATE_BOOLEAN ) ) {
	var_dump( $customer );

	$client = $this->plugin->get_client();

	$customers_finder  = new \Pronamic\WP\Twinfield\Customers\CustomerFinder( $client->get_finder() );
	$customers_service = new \Pronamic\WP\Twinfield\Customers\CustomerService( $client->get_xml_processor() );

	$customer_finder_results = $customers_finder->get_customers( $customer->get_name(), \Pronamic\WP\Twinfield\SearchFields::ADDRESS_FIELDS, 1, 10 );

	$addresses = $customer->get_addresses();

	$test = reset( $addresses );

	foreach ( $customer_finder_results as $customer_finder_result ) {
		$response = $customers_service->get_customer( $customer->get_office(), $customer_finder_result->get_code() );

		if ( $response->is_successful() ) {
			foreach ( $response->get_customer()->get_addresses() as $address ) {
				var_dump( $address );

				echo $test->similar(
					$address,
					array(
						'field_2',
						'postcode',
						'city',
						'country',
					)
				);
			}
		}
	}
}
// @codingStandardsIgnoreEnd
