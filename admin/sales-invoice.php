<?php if ( $sales_invoice ) : ?>

	<?php $header = $sales_invoice->get_header(); ?>

	<h3><?php printf( esc_html__( 'Invoice %d', 'twinfield' ), esc_html( $header->get_number() ) ); ?></h3>

	<table class="form-table">
		<tr>
			<th scope="row">
				<?php esc_html_e( 'Invoice Number', 'twinfield' ); ?>
			</th>
			<td>
				<?php echo esc_html( $header->get_number() ); ?>
			</td>
		</tr>

		<?php if ( $date = $header->get_date() ) : ?>

			<tr>
				<th scope="row">
					<?php esc_html_e( 'Date', 'twinfield' ); ?>
				</th>
				<td>
					<?php echo esc_html( date_i18n( 'D j M Y', $date->getTimestamp() ) ); ?>
				</td>
			</tr>

		<?php endif; ?>

		<?php if ( $due_date = $header->get_due_date() ) : ?>

			<tr>
				<th scope="row">
					<?php esc_html_e( 'Due Date', 'twinfield' ); ?>
				</th>
				<td>
					<?php echo esc_html( date_i18n( 'D j M Y', $due_date->getTimestamp() ) ); ?>
				</td>
			</tr>

		<?php endif; ?>

		<tr>
			<th scope="row">
				<?php esc_html_e( 'Office', 'twinfield' ); ?>
			</th>
			<td>
				<?php echo esc_html( $header->get_office() ); ?></td>
		</tr>
		<tr>
			<th scope="row">
				<?php esc_html_e( 'Type', 'twinfield' ); ?>
			</th>
			<td>
				<?php echo esc_html( $header->get_type() ); ?>
			</td>
		</tr>
		<tr>
			<th scope="row">
				<?php esc_html_e( 'Customer', 'twinfield' ); ?>
			</th>
			<td>
				<?php

				$customer = $header->get_customer();

				printf(
					'<a href="%s">%s</a>',
					esc_attr( twinfield_admin_view_customer_link( $customer ) ),
					esc_html( $customer )
				);

				?>
			</td>
		</tr>
		<tr>
			<th scope="row">
				<?php esc_html_e( 'Status', 'twinfield' ); ?>
			</th>
			<td>
				<?php echo esc_html( $header->get_status() ); ?>
			</td>
		</tr>
	</table>

	<?php $lines = $sales_invoice->get_lines(); ?>

	<table class="widefat">
		<thead>
			<tr>
				<th scope="col"><?php esc_html_e( 'Id', 'twinfield' ); ?></th>
				<th scope="col"><?php esc_html_e( 'Article', 'twinfield' ); ?></th>
				<th scope="col"><?php esc_html_e( 'Sub article', 'twinfield' ); ?></th>
				<th scope="col"><?php esc_html_e( 'Quantity', 'twinfield' ); ?></th>
				<th scope="col"><?php esc_html_e( 'Units', 'twinfield' ); ?></th>
				<th scope="col"><?php esc_html_e( 'Description', 'twinfield' ); ?></th>
				<th scope="col"><?php esc_html_e( 'Value Excl', 'twinfield' ); ?></th>
				<th scope="col"><?php esc_html_e( 'Vat Value', 'twinfield' ); ?></th>
				<th scope="col"><?php esc_html_e( 'Value Inc', 'twinfield' ); ?></th>
				<th scope="col"><?php esc_html_e( 'Free Text 1', 'twinfield' ); ?></th>
				<th scope="col"><?php esc_html_e( 'Free Text 2', 'twinfield' ); ?></th>
				<th scope="col"><?php esc_html_e( 'Free Text 3', 'twinfield' ); ?></th>
			</tr>
		</thead>

		<tbody>

			<?php foreach ( $lines as $line ) : ?>

				<tr>
					<td><?php echo esc_html( $line->get_id() ); ?></td>
					<td><?php echo esc_html( $line->get_article() ); ?></td>
					<td><?php echo esc_html( $line->get_subarticle() ); ?></td>
					<td><?php echo esc_html( $line->get_quantity() ); ?></td>
					<td><?php echo esc_html( $line->get_units() ); ?></td>
					<td><?php echo esc_html( $line->get_description() ); ?></td>
					<td><?php echo esc_html( twinfield_price( $line->get_value_excl() ) ); ?></td>
					<td><?php echo esc_html( twinfield_price( $line->get_vat_value() ) ); ?></td>
					<td><?php echo esc_html( twinfield_price( $line->get_value_inc() ) ); ?></td>
					<td><?php echo esc_html( $line->get_free_text_1() ); ?></td>
					<td><?php echo esc_html( $line->get_free_text_2() ); ?></td>
					<td><?php echo esc_html( $line->get_free_text_3() ); ?></td>
				</tr>

			<?php endforeach; ?>

		</tbody>
	</table>

<?php endif; ?>
