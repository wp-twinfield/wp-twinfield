<?php

// @see https://github.com/WordPress/WordPress/blob/3.8/wp-admin/options-permalink.php#L237

?>
<p>
	<?php

	printf(
		__( 'If you like, you may enter custom structures for your invoice and customer %s here. For example, using %s as your invoice base would make your invoice links like %s. If you leave these blank the defaults will be used.', 'twinfield' ),
		'<abbr title="Universal Resource Locator">URL</abbr>s',
		'<code>' . esc_html( _x( 'invoices', 'slug', 'twinfield' ) ) . '</code>',
		'<code>http://example.org/' . esc_html( _x( 'invoices', 'slug', 'twinfield' ) ) . '/140001/</code>'
	);

	?>
</p>
