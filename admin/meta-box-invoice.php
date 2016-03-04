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
</table>

<?php

if ( $response ) {
	global $twinfield_plugin;

	// echo '<pre>', htmlspecialchars( $response ), '</pre>';

	$xml = new DOMDocument();
	$xml->loadXML( $response );

	$xsl = new DOMDocument;
	$xsl->load( plugin_dir_path( $twinfield_plugin->file ) . '/admin/twinfield-salesinvoices.xsl' );

	$proc = new XSLTProcessor;
	$proc->importStyleSheet( $xsl );

	echo $proc->transformToXML( $xml ); //xss ok
}

?>
<p>
	<?php if ( $invoice_number ) : ?>

	    <a class="button" target="_blank" href="<?php echo esc_attr( twinfield_admin_view_invoice_link( $invoice_number ) ); ?>"><?php esc_html_e( 'View', 'twinfield' ); ?></a>

	<?php endif; ?>
</p>
