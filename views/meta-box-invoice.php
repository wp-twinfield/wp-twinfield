<?php

global $post;

$twinfield_respone = get_post_meta( $post->ID, '_twinfield_response', true );

?>
<script type="text/javascript">
	jQuery( function() {
		WP_Twinfield_Sync.invoice.ready();
	} );
</script>

<div id="TwinfieldInvoiceMetaBoxSync_MessageHolder"></div>

<table class="form-table">
	<tr>
		<th>
			<label for="twinfield_invoice_id"><?php _e( 'Invoice ID', 'twinfield' ); ?></label>
		</th>
		<td>
			<input id="twinfield_invoice_id" type="text" name="twinfield_invoice_id" value="<?php echo esc_attr( $invoice_id ); ?>" />
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
	<tr>
		<th>
			<label for="twinfield_invoice_type"><?php _e( 'Invoice Type', 'twinfield' ); ?></label>
		</th>
		<td>
			<input id="twinfield_invoice_type" type="text" name="twinfield_invoice_type" value="<?php echo esc_attr( $invoice_type ); ?>" />
		</td>
	</tr>
</table>

<?php if ( $twinfield_respone ) : ?>

	<pre><?php echo htmlspecialchars( $twinfield_respone ); ?></pre>

<?php endif; ?>

<?php if ( $invoice_id ) : ?>

    <a class="button" target="_blank" href="<?php echo twinfield_admin_view_invoice_link( $invoice_id ); ?>"><?php _e( 'View' ); ?></a>

<?php elseif ( $is_supported ) : ?>

	<?php submit_button( __( 'Create Invoice', 'twinfield' ), 'secondary', 'twinfield_create_invoice', false ); ?>

<?php else : ?>

    <p>
    	<?php _e( 'Looking for automatic synchronization?', 'twinfield' ); ?>
    </p>

<?php endif; ?>
