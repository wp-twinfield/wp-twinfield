<div class="wrap">
	<?php screen_icon( 'twinfield' ); ?>

	<h2>
		<?php echo esc_html( 'Twinfield' ); ?>
	</h2>

	<?php 

	$username = get_option( 'twinfield_username' );
	$password = get_option( 'twinfield_password' );
	$organisation = get_option( 'twinfield_organisation' );
	
	$twinfield_client = new Pronamic\Twinfield\TwinfieldClient();

	$result = $twinfield_client->logon( $username, $password, $organisation );

	$dimension = $twinfield_client->readDimension( 'dimensions', '11024', 'DEB', '1000' );
	
	?>
	<pre><?php var_dump( $dimension ); ?></pre>
</div>