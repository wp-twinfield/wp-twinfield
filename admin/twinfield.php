<div class="wrap">
	<?php screen_icon( 'twinfield' ); ?>

	<h2>
		<?php echo esc_html( 'Twinfield' ); ?>
	</h2>

	<?php 

	$username     = get_option( 'twinfield_username' );
	$password     = get_option( 'twinfield_password' );
	$organisation = get_option( 'twinfield_organisation' );

	?>
	<form action="https://login.twinfield.com/default.aspx" method="post" target="_blank">
		<p>
			<input name="txtUserID" type="hidden" value="<?php echo $username; ?>" />
			<input name="txtPassword" type="hidden" value="<?php echo $password; ?>" />
			<input name="txtcompanyID" type="hidden" value="<?php echo $organisation; ?>" />
			<input name="btnLogin" type="submit" value="Inloggen" />
		</p>
	</form>
</div>