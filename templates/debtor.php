<?php get_header(); ?>

<div id="container">
	<div id="content" role="main">
		<?php 
		
		global $twinfield_debtor; 

		$debtor = $twinfield_debtor;
		
		if ($debtor) : ?>
			
			<dl>
				<dt>Name</dt>
				<dd><?php echo $debtor->getName(); ?></dd>
				
				<dt>Addresses</dt>
				<dd>
					<ul>
						<?php foreach( $debtor->getAddresses() as $address ): ?>
							<li>
								<dl>
									<dt>Name</dt>
									<dd><?php echo $address->getName(); ?></dd>
	
									<dt>City</dt>
									<dd><?php echo $address->getCity(); ?></dd>
	
									<dt>Postal Code</dt>
									<dd><?php echo $address->getPostcode(); ?></dd>
	
									<dt>Telephone</dt>
									<dd><?php echo $address->getTelephone(); ?></dd>
								</dl>
							</li>
						<?php endforeach; ?>
					</ul>
				</dd>	
			</dl>
		
		<?php endif; ?>

	</div>
</div>

<?php get_footer(); ?>