<div class="wrap">
	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>

	<?php if ( $offices ) : ?>

		<table>
			<thead>
				<tr>
					<th scope="col"><?php esc_html_e( 'Code', 'twinfield' ); ?></th>
					<th scope="col"><?php esc_html_e( 'Shortname', 'twinfield' ); ?></th>
					<th scope="col"><?php esc_html_e( 'Name', 'twinfield' ); ?></th>
				</tr>
			</thead>

			<tbody>

				<?php foreach ( $offices as $office ): ?>

					<tr>
						<td><?php echo esc_html( $office->getCode() ); ?></td>
						<td><?php echo esc_html( $office->getShortName() ); ?></td>
						<td><?php echo esc_html( $office->getName() ); ?></td>
					</tr>

				<?php endforeach; ?>

			</tbody>
		</table>

	<?php endif; ?>

</div>
