<script type="text/javascript">
	;(function($) {
		$('.jFillLatestCustomerID').click(function(e) {
			e.preventDefault();
			$('.jCustomerID').val($('.jLatestCustomerID').val());
		});
	})(jQuery);
</script>
<h2><?php _e( 'Customer Form', 'twinfield' ); ?></h2>
<form method="POST" class="input-form">
	<?php echo $nonce; ?>
	<table class='form-table'>
		<tr>
			<th><?php _e( 'Customer ID', 'twinfield' ); ?></th>
			<td><input type="text" name="id" value="<?php echo $object->getID(); ?>" class="jCustomerID"/><a href="#" class="jFillLatestCustomerID"><?php _e( 'Newest Number', 'twinfield' ); ?></a><input type="hidden" class="jLatestCustomerID" value="<?php echo $form_extra['latest_customer_id']; ?>"/></td>
		</tr>
		<tr>
			<th><?php _e( 'Name', 'twinfield' ); ?></th>
			<td><input type="text" name="name" value="<?php echo $object->getName(); ?>"/></td>
		</tr>
		<tr>
			<th><?php _e( 'Website', 'twinfield' ); ?></th>
			<td><input type="text" name="website" value="<?php echo $object->getWebsite(); ?>"/></td>
		</tr>
		<tr>
			<th><?php _e( 'Due Days', 'twinfield' ); ?></th>
			<td><input type="text" name="duedays" value="<?php echo $object->getDueDays(); ?>" /></td>
		</tr>
		<tr>
			<th><?php _e( 'Electronic Invoice', 'twinfield' ); ?></th>
			<td><input type="checkbox" name="ebilling" value="true" <?php checked('true', $object->getEBilling() ); ?> /></td>
		</tr>
		<tr>
			<th><?php _e( 'Electronic Invoice Email', 'twinfield' ); ?></th>
			<td><input type="text" name="ebillmail" value="<?php echo $object->getEBillMail(); ?>"/></td>
		</tr>
		<tr>
			<th><?php _e( 'Vat Code', 'twinfield' ); ?></th>
			<td>
				<select name="vatcode">
					<?php foreach ( array( '#', 'VH', 'VL', 'VN' ) as $paycode ) : ?>
					<option value="<?php echo $paycode; ?>" <?php selected( $paycode, $object->getVatCode() ); ?>><?php echo $paycode; ?></option>
					<?php endforeach; ?>
				</select>
			</td>
		</tr>
	</table>
	<br/>
	<table class="widefat">
		<thead>
			<th><?php _e( 'Default?', 'twinfield' ); ?></th>
			<th><?php _e( 'Type', 'twinfield' ); ?></th>
			<th><?php _e( 'Name', 'twinfield' ); ?></th>
			<th><?php _e( 'Line 1', 'twinfield' ); ?></th>
			<th><?php _e( 'Line 2', 'twinfield' ); ?></th>
			<th><?php _e( 'Line 3', 'twinfield' ); ?></th>
			<th><?php _e( 'CoC Number', 'twinfield' ); ?></th>
			<th><?php _e( 'Postcode', 'twinfield' ); ?></th>
			<th><?php _e( 'City', 'twinfield' ); ?></th>
			<th><?php _e( 'Country', 'twinfield' ); ?></th>
			<th><?php _e( 'Email', 'twinfield' ); ?></th>
		</thead>
		<tbody class="jFormBuilderUI_TableBody">
			<?php $addresses = $object->getAddresses(); ?>
			<?php if ( ! empty( $addresses ) ) : ?>
				<?php $line_number = 1; ?>
				<?php foreach ( $addresses as $address ) : ?>
					<tr data-number="<?php echo $line_number; ?>">
						<td><input type="checkbox" name="addresses[<?php echo $line_number; ?>][default]" value="true" <?php checked( $address->getDefault(), 'true' ); ?> /></td>
						<td>
							<select name="addresses[<?php echo $line_number; ?>][type]">
								<?php foreach( array( 'invoice', 'postal', 'contact' ) as $type ) : ?>
									<option value="<?php echo $type; ?>" <?php selected( $type, $address->getType() ); ?>><?php _e( ucfirst( $type ), 'twinfield' ); ?></option>
								<?php endforeach; ?>
							</select>
						</td>
						<td><input type="text" name="addresses[<?php echo $line_number; ?>][name]" value="<?php echo $address->getName(); ?>" /></td>
						<td><input type="text" name="addresses[<?php echo $line_number; ?>][field1]" value="<?php echo $address->getField1(); ?>" /></td>
						<td><input type="text" name="addresses[<?php echo $line_number; ?>][field2]" value="<?php echo $address->getField2(); ?>" /></td>
						<td><input type="text" name="addresses[<?php echo $line_number; ?>][field3]" value="<?php echo $address->getField3(); ?>" /></td>
						<td><input type="text" name="addresses[<?php echo $line_number; ?>][field5]" value="<?php echo $address->getField5(); ?>" /></td>
						<td><input type="text" name="addresses[<?php echo $line_number; ?>][postcode]" value="<?php echo $address->getPostcode(); ?>" /></td>
						<td><input type="text" name="addresses[<?php echo $line_number; ?>][city]" value="<?php echo $address->getCity(); ?>" /></td>
						<td><input type="text" name="addresses[<?php echo $line_number; ?>][country]" value="<?php echo $address->getCountry(); ?>" /></td>
						<td><input type="text" name="addresses[<?php echo $line_number; ?>][email]" value="<?php echo $address->getEmail(); ?>" /></td>
					</tr>
					<?php $line_number++; ?>
				<?php endforeach; ?>
			<?php else: ?>
				<tr data-number="1">
					<td><input type="checkbox" name="addresses[1][default]" value="true" checked="checked"/></td>
					<td>
						<select name="addresses[1][type]">
							<?php foreach( array( 'invoice', 'postal', 'contact' ) as $type ) : ?>
								<option value="<?php echo $type; ?>"><?php _e( ucfirst( $type ), 'twinfield' ); ?></option>
							<?php endforeach; ?>
						</select>
					</td>
					<td><input type="text" name="addresses[1][name]" value="" /></td>
					<td><input type="text" name="addresses[1][field1]" value="" /></td>
					<td><input type="text" name="addresses[1][field2]" value="" /></td>
					<td><input type="text" name="addresses[1][field3]" value="" /></td>
					<td><input type="text" name="addresses[1][field5]" value="" /></td>
					<td><input type="text" name="addresses[1][postcode]" value="" /></td>
					<td><input type="text" name="addresses[1][city]" value="" /></td>
					<td><input type="text" name="addresses[1][country]" value="" /></td>
					<td><input type="text" name="addresses[1][email]" value="" /></td>
				</tr>
			<?php endif; ?>
		</tbody>
	</table>
	<br/>
	<a href="#" class="jAddLine">Add Line</a>
	<?php submit_button( __( 'Send', 'twinfield' ), 'primary',  'submit', false, array( 'style' => 'float:right;') ); ?>
</form>