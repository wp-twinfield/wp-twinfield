<?php

/**
 * Metabox view for the Twinfield Invoice Metabox.
 * 
 * @author Leon Rowland <leon@rowland.nl>
 */

?>
<script type="text/javascript">
    jQuery(function(){
        WP_Twinfield_Sync.invoice.ready();
    });
</script>
<!-- invoice meta box message holder start -->
<div id="TwinfieldInvoiceMetaBoxSync_MessageHolder">
    
</div>
<!-- invoice meta box message holder end -->

<!-- start invoice number input -->
<p>
	<label for="TwinfieldInvoiceMetaBoxSync_InvoiceID">
		<?php _e( 'Invoice ID', 'twinfield' ); ?>
	</label>
	<input id="TwinfieldInvoiceMetaBoxSync_InvoiceID" class="small-text" type="text" name="twinfield_invoice_id" value="<?php echo $invoice_id; ?>"
</p> 
<!-- end invoice number input -->

<!-- start invoice customer id input -->
<p>
    <label for="TwinfieldInvoiceMetaBoxSync_CustomerID">
        <?php _e( 'Customer ID', 'twinfield' ); ?>
    </label>
    <input id="TwinfieldInvoiceMetaBoxSync_CustomerID" type="text" name="twinfield_customer_id" value="<?php echo $customer_id; ?>" />
</p>
<!-- end invoice customer id input -->

<!-- start invoice type input -->
<p>
    <label for="TwinfieldInvoiceMetaBoxSync_InvoiceType">
        <?php _e( 'Invoice Type', 'twinfield' ); ?>
    </label>
    <input id="TwinfieldInvoiceMetaBoxSync_InvoiceType" type="text" name="twinfield_invoice_type" value="<?php echo $invoice_type; ?>" />
</p>
<!-- end invoice type input -->

<?php if ( $invoice_id ) : ?>
    <a class="button" target="_blank" href="<?php echo twinfield_admin_view_invoice_link( $invoice_id ); ?>"><?php _e( 'View' ); ?></a>
<?php endif; ?>

<?php if ( $is_supported ) : ?>
    <input id="TwinfieldInvoiceMetaBoxSync_SyncButton" class="button button-primary" type="submit" value="<?php _e( 'Sync', 'twinfield' ); ?>"/>
    <span id="TwinfieldInvoiceMetaBoxSync_SpinnerHolder"></span>
<?php else : ?>
    <p><?php _e( 'Looking for automatic synchronization?', 'twinfield' ); ?></p>
<?php endif; ?>

