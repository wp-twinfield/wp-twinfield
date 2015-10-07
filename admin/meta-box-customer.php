<?php wp_nonce_field( 'twinfield_customer', 'twinfield_customer_nonce' ); ?>

<table class="form-table">
	<tr>
		<th scope="row">
			<label for="twinfield_customer_id"><?php esc_html_e( 'Customer ID', 'twinfield' ); ?></label>
		</th>
		<td>
			<input type="text" id="twinfield_customer_id" name="twinfield_customer_id" value="<?php echo esc_attr( $twinfield_customer_id ); ?>" />
		</td>
	</tr>
</table>

<?php

var_dump( $customer );

do_action( 'twinfield_customer_meta_box' );
