<?php echo $nonce; ?>
<table class="form-table">
	<tr>
		<th><label for="twinfield_customer_id"><?php _e( 'Twinfield Customer ID', 'twinfield' ); ?></label></th>
		<td><input type="text" id="twinfield_customer_id" name="twinfield_customer_id" value="<?php echo $twinfield_customer_id; ?>"/></td>
	</tr>
</table>

<?php do_action( 'twinfield_customer_meta_box' ); ?>