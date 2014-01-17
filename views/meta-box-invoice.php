<script type="text/javascript">
	jQuery( function() {
		WP_Twinfield_Sync.invoice.ready();
	} );
</script>

<div id="TwinfieldInvoiceMetaBoxSync_MessageHolder"></div>

<table class="form-table">
	<tr>
		<th>
			<label for="TwinfieldInvoiceMetaBoxSync_InvoiceID"><?php _e( 'Invoice ID', 'twinfield' ); ?></label>
		</th>
		<td>
			<input id="TwinfieldInvoiceMetaBoxSync_InvoiceID" type="text" name="twinfield_invoice_id" value="<?php echo $invoice_id; ?>"
		</td>
	</tr>
	<tr>
		<th>
			<label for="TwinfieldInvoiceMetaBoxSync_CustomerID"><?php _e( 'Customer ID', 'twinfield' ); ?></label>
		</th>
		<td>
			<input id="TwinfieldInvoiceMetaBoxSync_CustomerID" type="text" name="twinfield_customer_id" value="<?php echo $customer_id; ?>" />
		</td>
	</tr>
	<tr>
		<th>
			<label for="TwinfieldInvoiceMetaBoxSync_InvoiceType"><?php _e( 'Invoice Type', 'twinfield' ); ?></label>
		</th>
		<td>
			<input id="TwinfieldInvoiceMetaBoxSync_InvoiceType" type="text" name="twinfield_invoice_type" value="<?php echo $invoice_type; ?>" />
		</td>
	</tr>
</table>

<?php if ( $invoice_id ) : ?>

    <a class="button" target="_blank" href="<?php echo twinfield_admin_view_invoice_link( $invoice_id ); ?>"><?php _e( 'View' ); ?></a>

<?php endif; ?>

<?php if ( $is_supported ) : ?>

    <input id="TwinfieldInvoiceMetaBoxSync_SyncButton" class="button button-primary" type="submit" value="<?php _e( 'Sync', 'twinfield' ); ?>"/>

    <span id="TwinfieldInvoiceMetaBoxSync_SpinnerHolder"></span>

<?php else : ?>

    <p>
    	<?php _e( 'Looking for automatic synchronization?', 'twinfield' ); ?>
    </p>

<?php endif; ?>
