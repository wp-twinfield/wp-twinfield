<?php

$invoice_number = get_post_meta( $post->ID, '_twinfield_invoice_number', true );

?>
<table class="form-table">
	<tr>
		<th scope="row">
			<label for="twinfield_invoice_number"><?php esc_html_e( 'Invoice Number', 'twinfield' ); ?></label>
		</th>
		<td>
			<input id="twinfield_invoice_number" type="text" name="twinfield_invoice_number" value="<?php echo esc_attr( $invoice_number ); ?>" />

			<?php if ( empty( $invoice_number ) ) : ?>

				<?php

				submit_button(
					__( 'Create Invoice', 'twinfield' ),
					'secondary',
					'twinfield_create_invoice',
					false
				);

				?>

			<?php else : ?>

				<a class="button" target="_blank" href="<?php echo esc_attr( twinfield_admin_view_invoice_link( $invoice_number ) ); ?>"><?php esc_html_e( 'View Invoice', 'twinfield' ); ?></a>

			<?php endif; ?>
		</td>
	</tr>
</table>

<?php

$response = get_post_meta( $post->ID, '_twinfield_invoice_response_xml', true );

if ( $response ) {
	global $twinfield_plugin;

	$xml = new DOMDocument();
	$xml->loadXML( $response );

	$xsl = new DOMDocument();
	$xsl->load( plugin_dir_path( $twinfield_plugin->file ) . '/admin/twinfield-salesinvoices.xsl' );

	$proc = new XSLTProcessor();
	$proc->importStyleSheet( $xsl );

	echo $proc->transformToXML( $xml ); // xss ok
}

?>
