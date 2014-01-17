<div class="wrap">
	<?php screen_icon( 'twinfield' ); ?>

	<h2><?php echo get_admin_page_title(); ?></h2>

	<?php if ( ! filter_has_var( INPUT_GET, 'twinfield_customer_id' ) || isset( $error_messages ) ) : ?>

		<form method="get" action="">
			<input type="hidden" name="page" value="twinfield_customers" />

			<h3><?php _e( 'Search for customers', 'twinfield' ); ?></h3>

			<table class="form-table">
				<tr>
					<th><?php _e( 'Customer ID', 'twinfield' ); ?></th>
					<td>
						<input type="text" name="twinfield_customer_id" value="<?php echo filter_input( INPUT_GET, 'twinfield_customer_id', FILTER_VALIDATE_INT ); ?>" />
					</td>
				</tr>
			</table>

			<?php submit_button( __( 'Search', 'twinfield' ), 'primary', null ); ?>
	    </form>

	<?php endif; ?>
    
	<?php if ( ! isset( $error_messages ) && false !== $customer ) : ?>

		<h3><?php printf( __( 'Customer %s', 'twinfield' ), $customer->getID() ); ?></h3>

		<table class="widefat">
			<tr>
				<th><strong><?php _e( 'Name', 'twinfield' ); ?></strong></th>
				<td><?php echo $customer->getName(); ?></td>
			</tr>
			<tr>
				<th><strong><?php _e( 'Website', 'twinfield' ); ?></strong></th>
				<td><?php echo $customer->getWebsite(); ?></td>
			</tr>
			<tr>
				<th><strong><?php _e( 'Addresses', 'twinfield' ); ?></strong></th>
				<td>
					<table class="widefat">
						<thead>
							<tr>
								<th><?php _e( 'Name', 'twinfield' ); ?></th>
								<th><?php _e( 'City', 'twinfield' ); ?></th>
								<th><?php _e( 'Postal Code', 'twinfield' ); ?></th>
								<th><?php _e( 'Telephone', 'twinfield' ); ?></th>
							</tr>
						</thead>

						<tbody>

							<?php foreach ( $customer->getAddresses() as $address ) : ?>
	
								<tr>
									<td><?php echo $address->getName(); ?></td>
									<td><?php echo $address->getCity(); ?></td>
									<td><?php echo $address->getPostcode(); ?></td>
									<td><?php echo $address->getTelephone(); ?></td>
								</tr>

							<?php endforeach; ?>

						</tbody>
					</table>
				</td>
			</tr>
		</table>

	<?php else : ?>

		<?php if ( ! empty( $error_messages ) ) : ?>
			<?php foreach ( $error_messages as $error_message ) : ?>
				<div class="error">
					<p><?php echo $error_message; ?></p>
				</div>
			<?php endforeach; ?>
		<?php endif; ?>

	<?php endif; ?>

</div>