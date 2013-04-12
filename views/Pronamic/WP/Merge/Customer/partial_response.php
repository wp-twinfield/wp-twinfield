<?php if ( ! empty( $customers ) ) : ?>
	<?php foreach ( $customers as $customer ) : ?>
	<li><?php echo $customer->getID(); ?> : <?php echo $customer->getCocNumber(); ?></li>
	<?php endforeach; ?>
<?php endif; ?>
