<?php get_header(); ?>

<div id="container">
	<div id="content" role="main">
	
<dl class="pronamic_twinfield_customer_single">
	<!-- Customer Name Start -->
	<dt><?php _e( 'Name', 'twinfield' ); ?></dt>
	<dd><?php echo $customer->getName(); ?></dd>
	<!-- Customer Name End -->
	
	<!-- Customer Addresses Start -->
	<dt><?php _e( 'Addresses', 'twinfield' ); ?></dt>
	<dd>
		<!-- Start .pronamic_twinfield_customer_single_address_list -->
		<ul class="pronamic_twinfield_customer_single_address_list">
			<?php foreach ( $customer->getAddresses() as $address ) : ?>
			<li>
				<dl>
					<!-- Customer Address Name Start -->
					<dt><?php _e( 'Name', 'twinfield' ); ?></dt>
					<dd><?php echo $address->getName(); ?></dd>
					<!-- Customer Address Name End-->
					
					<!-- CUstomer Address City Start -->
					<dt><?php _e( 'City', 'twinfield' ); ?></dt>
					<dd><?php echo $address->getCity(); ?></dd>
					<!-- Customer Address City End -->
					
					<!-- Customer Address Postcode Start -->
					<dt><?php _e( 'Postal Code', 'twinfield' ); ?></dt>
					<dd><?php echo $address->getPostcode(); ?></dd>
					<!-- Customer Address Postcode End -->
					
					<!-- Customer Address Telephone Start -->
					<dt><?php _e( 'Telephone', 'twinfield' ); ?></dt>
					<dd><?php echo $address->getTelephone(); ?></dd>
					<!-- Customer Address Telephone End -->
				</dl>
			</li>
			<?php endforeach; ?>
		</ul>
		<!-- End .pronamic_twinfield_customer_single_address_list -->
	</dd>
	<!-- Customer Addresses End -->
</dl>

	</div>
</div>

<?php get_footer(); ?>