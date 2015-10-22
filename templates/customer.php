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
				<dl class="dl-horizontal">
					<dt><?php esc_html_e( 'Name', 'twinfield' ); ?></dt>
					<dd><?php echo esc_html( $twinfield_customer->get_name() ); ?></dd>

					<dt><?php esc_html_e( 'Office', 'twinfield' ); ?></dt>
					<dd><?php echo esc_html( $twinfield_customer->get_office() ); ?></dd>
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

			</div>
		</div>
	</div>
</div>

<?php get_footer(); ?>
