<div class="wrap">
	<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

	<?php if ( $offices ) : ?>

		<table class="widefat">
			<thead>
				<tr>
					<th scope="col"><?php esc_html_e( 'Code', 'twinfield' ); ?></th>
					<th scope="col"><?php esc_html_e( 'Shortname', 'twinfield' ); ?></th>
					<th scope="col"><?php esc_html_e( 'Name', 'twinfield' ); ?></th>
				</tr>
			</thead>

			<tbody>

				<?php foreach ( $offices as $office ) : ?>

					<tr>
						<td><?php echo esc_html( $office->get_code() ); ?></td>
						<td><?php echo esc_html( $office->get_shortname() ); ?></td>
						<td><?php echo esc_html( $office->get_name() ); ?></td>
					</tr>

				<?php endforeach; ?>

			</tbody>
		</table>

	<?php endif; ?>

</div>
