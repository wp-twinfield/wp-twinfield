<?php get_header(); ?>

<div id="container">
	<div id="content" role="main">

		<div class="page-header">
			<h1><?php echo esc_html( $twinfield_customer->get_name() ); ?></h1>
		</div>

		<div class="panel">
			<header>
				<h3><?php esc_html_e( 'Contact', 'twinfield' ); ?></h3>
			</header>

			<div class="content">
				<dl class="row">
					<dt class="col-sm-2"><?php esc_html_e( 'Name', 'twinfield' ); ?></dt>
					<dd class="col-sm-10"><?php echo esc_html( $twinfield_customer->get_name() ); ?></dd>

					<dt class="col-sm-2"><?php esc_html_e( 'Office', 'twinfield' ); ?></dt>
					<dd class="col-sm-10"><?php echo esc_html( $twinfield_customer->get_office() ); ?></dd>
				</dl>
			</div>
		</div>

		<div class="panel">
			<header>
				<h3><?php esc_html_e( 'Financials', 'twinfield' ); ?></h3>
			</header>

			<div class="content">
				<dl class="row">
					<dt class="col-sm-2"><?php esc_html_e( 'Due Days', 'twinfield' ); ?></dt>
					<dd class="col-sm-10"><?php echo esc_html( $twinfield_customer->get_financials()->get_due_days() ); ?></dd>

					<dt class="col-sm-2"><?php esc_html_e( 'Electronic Billing', 'twinfield' ); ?></dt>
					<dd class="col-sm-10"><?php echo esc_html( $twinfield_customer->get_financials()->get_ebilling() ? __( 'Yes', 'twinfield' ) : __( 'No', 'twinfield' ) ); ?></dd>

					<dt class="col-sm-2"><?php esc_html_e( 'Email', 'twinfield' ); ?></dt>
					<dd class="col-sm-10"><?php echo esc_html( $twinfield_customer->get_financials()->get_ebillmail() ); ?></dd>
				</dl>
			</div>
		</div>

		<div class="panel">
			<header>
				<h3><?php esc_html_e( 'Credit Management', 'twinfield' ); ?></h3>
			</header>

			<div class="content">
				<dl class="row">
					<dt class="col-sm-2"><?php esc_html_e( 'Send Reminder', 'twinfield' ); ?></dt>
					<dd class="col-sm-10"><?php

					$send_reminder = $twinfield_customer->get_credit_management()->get_send_reminder();

					switch ( $send_reminder ) {
						case 'true' :
							esc_html_e( 'Yes', 'twinfield' );
							break;
						case 'email' :
							esc_html_e( 'Yes, by e-mail', 'twinfield' );
							break;
						case 'false' :
							esc_html_e( 'No', 'twinfield' );
							break;
						default :
							echo esc_html( $send_reminder );
							break;
					}

					?></dd>

					<dt class="col-sm-2"><?php esc_html_e( 'Reminder Email', 'twinfield' ); ?></dt>
					<dd class="col-sm-10"><?php echo esc_html( $twinfield_customer->get_credit_management()->get_reminder_email() ); ?></dd>
				</dl>
			</div>
		</div>

		<div class="panel">
			<header>
				<h3><?php esc_html_e( 'Addresses', 'twinfield' ); ?></h3>
			</header>

			<div class="content">

				<?php foreach ( $twinfield_customer->get_addresses() as $address ) : ?>

					<table class="table table-striped">
						<col width="150" />

						<tr>
							<th scope="row"><?php esc_html_e( 'Default', 'twinfield' ); ?></th>
							<td><?php $address->is_default() ? esc_html_e( 'Yes', 'twinfield' ) : esc_html_e( 'No', 'twinfield' ); ?></td>
						</tr>
						<tr>
							<th scope="row"><?php esc_html_e( 'Type', 'twinfield' ); ?></th>
							<td><?php echo esc_html( $address->get_type() ); ?></td>
						</tr>
						<tr>
							<th scope="row"><?php esc_html_e( 'Contact', 'twinfield' ); ?></th>
							<td><?php echo esc_html( $address->get_contact() ); ?></td>
						</tr>
						<tr>
							<th scope="row"><?php esc_html_e( 'Name', 'twinfield' ); ?></th>
							<td><?php echo esc_html( $address->get_name() ); ?></td>
						</tr>
						<tr>
							<th scope="row"><?php esc_html_e( 'Address', 'twinfield' ); ?></th>
							<td><?php echo esc_html( $address->get_field_2() ); ?></td>
						</tr>
						<tr>
							<th scope="row"><?php esc_html_e( 'Postal Code', 'twinfield' ); ?></th>
							<td><?php echo esc_html( $address->get_postcode() ); ?></td>
						</tr>
						<tr>
							<th scope="row"><?php esc_html_e( 'City', 'twinfield' ); ?></th>
							<td><?php echo esc_html( $address->get_city() ); ?></td>
						</tr>
						<tr>
							<th scope="row"><?php esc_html_e( 'Country', 'twinfield' ); ?></th>
							<td><?php echo esc_html( $address->get_country() ); ?></td>
						</tr>
						<tr>
							<th scope="row"><?php esc_html_e( 'Email', 'twinfield' ); ?></th>
							<td><?php echo esc_html( $address->get_email() ); ?></td>
						</tr>
						<tr>
							<th scope="row"><?php esc_html_e( 'Phone Number', 'twinfield' ); ?></th>
							<td><?php echo esc_html( $address->get_telephone() ); ?></td>
						</tr>
						<tr>
							<th scope="row"><?php esc_html_e( 'Fax Number', 'twinfield' ); ?></th>
							<td><?php echo esc_html( $address->get_telefax() ); ?></td>
						</tr>
						<tr>
							<th scope="row"><?php esc_html_e( 'VAT Number', 'twinfield' ); ?></th>
							<td><?php echo esc_html( $address->get_field_4() ); ?></td>
						</tr>
						<tr>
							<th scope="row"><?php esc_html_e( 'COC Number', 'twinfield' ); ?></th>
							<td><?php echo esc_html( $address->get_field_5() ); ?></td>
						</tr>
					</table>

					<hr />

				<?php endforeach; ?>

				<?php var_dump( $twinfield_customer ); ?>

			</div>
		</div>
	</div>
</div>

<?php get_footer(); ?>
