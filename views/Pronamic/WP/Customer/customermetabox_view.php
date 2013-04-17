<?php echo $nonce; ?>
<table class="form-table">
	<tr>
		<th><?php _e( 'Twinfield Customer ID', 'twinfield' ); ?></th>
		<td><input type="text" name="twinfield_customer_id" value="<?php echo $twinfield_customer_id; ?>"/></td>
	</tr>
</table>

<?php do_action( 'twinfield_customer_meta_box' ); ?>