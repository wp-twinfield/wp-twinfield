<?php get_header(); ?>

<div id="container">
	<div id="content" role="main">

		<dl class="pronamic_twinfield_customer_single">
			<dt><?php esc_html_e( 'Name', 'twinfield' ); ?></dt>
			<dd><?php echo esc_html( $twinfield_customer->get_name() ); ?></dd>

			<dt><?php esc_html_e( 'Addresses', 'twinfield' ); ?></dt>
			<dd>
				<ul class="pronamic_twinfield_customer_single_address_list">

					<?php foreach ( $twinfield_customer->get_addresses() as $address ) : ?>

						<li>
							<dl>
								<dt><?php esc_html_e( 'Name', 'twinfield' ); ?></dt>
								<dd><?php echo esc_html( $address->get_name() ); ?></dd>

								<dt><?php esc_html_e( 'City', 'twinfield' ); ?></dt>
								<dd><?php echo esc_html( $address->get_city() ); ?></dd>

								<dt><?php esc_html_e( 'Postal Code', 'twinfield' ); ?></dt>
								<dd><?php echo esc_html( $address->get_postcode() ); ?></dd>

								<dt><?php esc_html_e( 'Telephone', 'twinfield' ); ?></dt>
								<dd><?php echo esc_html( $address->get_telephone() ); ?></dd>
							</dl>
						</li>

					<?php endforeach; ?>

				</ul>
			</dd>
		</dl>

	</div>
</div>

<?php get_footer(); ?>
