<?php

/**
 * User profile.
 *
 * @since 1.1.6
 * @link https://github.com/WordPress/WordPress/blob/4.5.2/wp-admin/user-edit.php#L578-L600
 */

$customer_id = get_user_meta( $user->ID, 'twinfield_customer_id', true );

?>
<h2><?php esc_html_e( 'Twinfield', 'twinfield' ); ?></h2>

<table class="form-table">
	<tr>
		<th>
			<?php esc_html_e( 'Customer', 'twinfield' ); ?>
		</th>
		<td>
			<select name="twinfield_customer_id" id="twinfield_customer_id" class="regular-text twinfield-customer-select">
				<option value=""></option>
				<option value="<?php echo esc_attr( $customer_id ); ?>"><?php echo esc_html( $customer_id ); ?></option>
			</select>
		</td>
	</tr>
</table>
