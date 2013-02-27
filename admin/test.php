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

	$debtor = $twinfield_client->read_debtor( '11024', '1000' );
	
	?>
	
	<pre><?php var_dump( $debtor ); ?></pre>

	<?php /*
	
	$xml = '<salesinvoices>
	<salesinvoice>
		<header>
			<invoicetype>FACTUUR</invoicetype>
			<customer>1000</customer>
		</header>
		<lines>
			<line>
				<quantity>1</quantity>
				<article>4</article>
				<subarticle>118</subarticle>
			</line>
		</lines>
	</salesinvoice>
</salesinvoices>
	';

	$result = $twinfield_client->processXmlString( $xml );
	
	$result_xml = $result->ProcessXmlStringResult;
	*/
	
	$result_xml = '';
	?>
	<pre><?php echo htmlentities( $result_xml ); ?></pre>
</div>