<?php if ( ! empty( $customers ) ) : ?>
	<?php foreach ( $customers as $customer ) : ?>
	<?php
	
		// Get the CoC Number
		$coc_number = $customer->getCocNumber();
		
		// Check its really there
		if ( empty( $coc_number ) ) {
			
			// Go through all the addresses
			foreach ( $customer->getAddresses() as $address ) {
				
				// Get this address' coc number
				$_coc_number = $address->getField5();
				
				// Is it there?
				if ( ! empty( $_coc_number ) )
					$coc_number = $_coc_number;
			}
		}
	?>
	<li><?php echo $customer->getID(); ?> : <?php echo $coc_number; ?></li>
	<?php endforeach; ?>
<?php endif; ?>
