<?php

global $post;

$is_supported   = \Pronamic\WP\Twinfield\Invoice\InvoiceMetaBoxFactory::supported( $post->post_type );

$invoice_number = get_post_meta( $post->ID, '_twinfield_invoice_number', true );
$customer_id    = get_post_meta( $post->ID, '_twinfield_customer_id', true );

$response       = get_post_meta( $post->ID, '_twinfield_response', true );

?>
<table class="form-table">
	<tr>
		<th scope="row">
			<label for="twinfield_invoice_number"><?php esc_html_e( 'Invoice Number', 'twinfield' ); ?></label>
		</th>
		<td>
			<input id="twinfield_invoice_number" type="text" name="twinfield_invoice_number" value="<?php echo esc_attr( $invoice_number ); ?>" />

			<?php if ( empty( $invoice_number ) ) : ?>

				<span class="description"><br /><?php esc_html_e( 'You can manullay enter an Twinfield invoice number or use the "Create Invoice" button below.', 'twinfield' ); ?></span>

			<?php endif; ?>
		</td>
	</tr>
	<tr>
		<th scope="row">
			<label for="twinfield_customer_id"><?php esc_html_e( 'Customer ID', 'twinfield' ); ?></label>
		</th>
		<td>
			<input id="twinfield_customer_id" type="text" name="twinfield_customer_id" value="<?php echo esc_attr( $customer_id ); ?>" />
		</td>
	</tr>
</table>

<?php

if ( $response ) {
	$plugin = Pronamic_WP_TwinfieldPlugin_Plugin::get_instance();

	// echo '<pre>', htmlspecialchars( $response ), '</pre>';

	$xml = new DOMDocument();
	$xml->loadXML( $response );

	$xsl = new DOMDocument;
	$xsl->load( plugin_dir_path( $plugin->file ) . '/admin/twinfield-salesinvoices.xsl' );

	$proc = new XSLTProcessor;
	$proc->importStyleSheet( $xsl );

	echo $proc->transformToXML( $xml ); //xss ok
}

?>
<p>
	<?php if ( $invoice_number ) : ?>

	    <a class="button" target="_blank" href="<?php echo twinfield_admin_view_invoice_link( $invoice_number ); ?>"><?php esc_html_e( 'View', 'twinfield' ); ?></a>

	<?php elseif ( $is_supported ) : ?>

		<?php submit_button( __( 'Create Invoice', 'twinfield' ), 'secondary', 'twinfield_create_invoice', false ); ?>

	<?php else : ?>

	    <p>
	    	<?php esc_html_e( 'Looking for automatic synchronization?', 'twinfield' ); ?>
	    </p>

	<?php endif; ?>
</p>
