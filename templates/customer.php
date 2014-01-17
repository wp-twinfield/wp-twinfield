<?php get_header(); ?>

<div id="container">
	<div id="content" role="main">
			
		<dl class="pronamic_twinfield_customer_single">
			<dt><?php _e( 'Name', 'twinfield' ); ?></dt>
			<dd><?php echo $twinfield_customer->getName(); ?></dd>

			<dt><?php _e( 'Addresses', 'twinfield' ); ?></dt>
			<dd>
				<ul class="pronamic_twinfield_customer_single_address_list">

					<?php foreach ( $twinfield_customer->getAddresses() as $address ) : ?>

						<li>
							<dl>
								<dt><?php _e( 'Name', 'twinfield' ); ?></dt>
								<dd><?php echo $address->getName(); ?></dd>
	
								<dt><?php _e( 'City', 'twinfield' ); ?></dt>
								<dd><?php echo $address->getCity(); ?></dd>
								
								<dt><?php _e( 'Postal Code', 'twinfield' ); ?></dt>
								<dd><?php echo $address->getPostcode(); ?></dd>
								
								<dt><?php _e( 'Telephone', 'twinfield' ); ?></dt>
								<dd><?php echo $address->getTelephone(); ?></dd>
							</dl>
						</li>

					<?php endforeach; ?>

				</ul>
			</dd>
		</dl>

	</div>
</div>

<?php get_footer(); ?>