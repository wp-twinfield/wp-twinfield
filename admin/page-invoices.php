<div class="wrap">
	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>

	<?php if ( ! filter_has_var( INPUT_GET, 'twinfield_invoice_id' ) || empty( $twinfield_response ) || ! $twinfield_response->is_successful() ) : ?>

		<form method="get" action="">
			<input type="hidden" name="page" value="twinfield_invoices" />

			<h3><?php esc_html_e( 'Request Invoice', 'twinfield' ); ?></h3>

			<table class="form-table">
				<tr>
					<th scope="row">
						<?php esc_html_e( 'Invoice ID', 'twinfield' ); ?>
					</th>
					<td>
						<input type="text" name="twinfield_invoice_id" value="<?php echo esc_attr( filter_input( INPUT_GET, 'twinfield_invoice_id', FILTER_VALIDATE_INT ) ); ?>" />
					</td>
				</tr>
			</table>

			<?php submit_button( __( 'Request', 'twinfield' ), 'primary', null ); ?>
		</form>

	<?php endif; ?>

	<?php

	if ( $twinfield_response ) {
		if ( $twinfield_response->is_successful() ) {
			$sales_invoice = $twinfield_response->get_sales_invoice();

			include plugin_dir_path( $this->plugin->file ) . 'admin/sales-invoice.php';
		} else {
			esc_html_e( 'Could not connect to Twinfield.', 'twinfield' );
		}
	}

	?>
</div>
