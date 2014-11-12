<?php

global $post;

$is_supported   = \Pronamic\WP\Twinfield\Invoice\InvoiceMetaBoxFactory::supported( $post->post_type );

$invoice_number = get_post_meta( $post->ID, '_twinfield_invoice_number', true );
$customer_id    = get_post_meta( $post->ID, '_twinfield_customer_id', true );

$response       = get_post_meta( $post->ID, '_twinfield_response', true );

?>
<table class="form-table">
	<tr>
		<th>
			<label for="twinfield_invoice_number"><?php _e( 'Invoice Number', 'twinfield' ); ?></label>
		</th>
		<td>
			<input id="twinfield_invoice_number" type="text" name="twinfield_invoice_number" value="<?php echo esc_attr( $invoice_number ); ?>" />
		</td>
	</tr>
	<tr>
		<th>
			<label for="twinfield_customer_id"><?php _e( 'Customer ID', 'twinfield' ); ?></label>
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

	echo $proc->transformToXML( $xml );
}

?>
<p>
	<?php if ( $invoice_number ) : ?>

	    <a class="button" target="_blank" href="<?php echo twinfield_admin_view_invoice_link( $invoice_number ); ?>"><?php _e( 'View' ); ?></a>

	<?php elseif ( $is_supported ) : ?>

		<?php submit_button( __( 'Create Invoice', 'twinfield' ), 'secondary', 'twinfield_create_invoice', false ); ?>

	<?php else : ?>

	    <p>
	    	<?php _e( 'Looking for automatic synchronization?', 'twinfield' ); ?>
	    </p>

	<?php endif; ?>
</p>
