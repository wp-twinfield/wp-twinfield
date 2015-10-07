<div class="wrap">
	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>

	<?php

	$username = get_option( 'twinfield_username' );
	$password = get_option( 'twinfield_password' );
	$organisation = get_option( 'twinfield_organisation' );

	// $twinfield_client = new Pronamic\Twinfield\TwinfieldClient();

	// $result = $twinfield_client->logon( $username, $password, $organisation );

	// $offices = $twinfield_client->getOffices();

	$offices = false;

	if ( $offices ) : ?>

		<table>
			<thead>
				<tr>
					<th scope="col"><?php _e( 'Code', 'twinfield' ); ?></th>
					<th scope="col"><?php _e( 'Shortname', 'twinfield' ); ?></th>
					<th scope="col"><?php _e( 'Name', 'twinfield' ); ?></th>
				</tr>
			</thead>

			<tbody>

				<?php foreach ( $offices as $office ): ?>

					<tr>
						<td><?php echo $office->getCode(); ?></td>
						<td><?php echo $office->getShortName(); ?></td>
						<td><?php echo $office->getName(); ?></td>
					</tr>

				<?php endforeach; ?>

			</tbody>
		</table>

	<?php endif; ?>

</div>
