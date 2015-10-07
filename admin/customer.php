<?php if ( $customer ) : ?>

	<h3><?php esc_html_e( 'General', 'twinfield' ); ?></h3>

	<table class="form-table">
		<tr>
			<th scope="row">
				<?php esc_html_e( 'Code', 'twinfield' ); ?>
			</th>
			<td>
				<?php echo esc_html( $customer->get_code() ); ?>
			</td>
		</tr>
		<tr>
			<th scope="row">
				<?php esc_html_e( 'Name', 'twinfield' ); ?>
			</th>
			<td>
				<?php echo esc_html( $customer->get_name() ); ?>
			</td>
		</tr>
		<tr>
			<th scope="row">
				<?php esc_html_e( 'Website', 'twinfield' ); ?>
			</th>
			<td>
				<?php // echo esc_html( $customer->get_website() ); ?>
			</td>
		</tr>
	</table>

	<?php if ( $financials = $customer->get_financials() ) : ?>

		<h3><?php esc_html_e( 'Financials', 'twinfield' ); ?></h3>

		<table class="form-table">
			<tr>
				<th scope="row">
					<?php esc_html_e( 'Due Days', 'twinfield' ); ?>
				</th>
				<td>
					<?php echo esc_html( $financials->get_due_days() ); ?>
				</td>
			</tr>
		</table>

	<?php endif; ?>

	<?php if ( $credit_management = $customer->get_credit_management() ) : ?>

		<h3><?php esc_html_e( 'Credit Management', 'twinfield' ); ?></h3>

		<table class="form-table">
			<tr>
				<th scope="row">
					<?php esc_html_e( 'Send Reminder', 'twinfield' ); ?>
				</th>
				<td>
					<?php echo esc_html( $credit_management->get_send_reminder() ); ?>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<?php esc_html_e( 'Reminder Email', 'twinfield' ); ?>
				</th>
				<td>
					<?php echo esc_html( $credit_management->get_reminder_email() ); ?>
				</td>
			</tr>
		</table>

	<?php endif; ?>

	<h3><?php esc_html_e( 'Addresses', 'twinfield' ); ?></h3>

	<table class="widefat">
		<thead>
			<tr>
				<th><?php esc_html_e( 'Name', 'twinfield' ); ?></th>
				<th><?php esc_html_e( 'City', 'twinfield' ); ?></th>
				<th><?php esc_html_e( 'Postal Code', 'twinfield' ); ?></th>
				<th><?php esc_html_e( 'Telephone', 'twinfield' ); ?></th>
			</tr>
		</thead>

		<tbody>

			<?php foreach ( $customer->get_addresses() as $address ) : ?>

				<tr>
					<td><?php echo esc_html( $address->get_name() ); ?></td>
					<td><?php echo esc_html( $address->get_city() ); ?></td>
					<td><?php echo esc_html( $address->get_postcode() ); ?></td>
					<td><?php echo esc_html( $address->get_telephone() ); ?></td>
				</tr>

			<?php endforeach; ?>

		</tbody>
	</table>

<?php endif; ?>
