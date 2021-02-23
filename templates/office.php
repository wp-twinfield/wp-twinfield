<?php

namespace Pronamic\WP\Twinfield\Plugin;

global $twinfield_office_template;

if ( ! \is_object( $twinfield_office_template ) ) {
	return;
}

\get_header();

?>
<h1><?php echo \esc_html( $twinfield_office_template->office_code ); ?></h1>

<div class="card mt-4">
	<div class="card-header">
		<a name="twinfield-xml"></a>
		<?php esc_html_e( 'Twinfield XML', 'lookup' ); ?>
	</div>

	<?php

	$document = new \DOMDocument();

	$document->preserveWhiteSpace = false;
	$document->formatOutput       = true;

	$document->loadXML( $twinfield_office_template->xml );

	$xml = $document->saveXML();

	?>

	<textarea id="test"><?php echo esc_textarea( $xml ); ?></textarea>

	<script src="https://unpkg.com/codemirror@5.59.3/lib/codemirror.js"></script>
	<script src="https://unpkg.com/codemirror@5.59.3/mode/xml/xml.js"></script>

	<link rel="stylesheet" type="text/css" href="https://unpkg.com/codemirror@5.59.3/lib/codemirror.css" />

	<style type="text/css">
		.CodeMirror {
			height: 100%;
		}
	</style>

	<script type="text/javascript">
		var textarea = document.getElementById( 'test' );

		editor = CodeMirror.fromTextArea( textarea, {
			lineNumbers: true,
			mode: 'application/xml'
		} );
	</script>
</div>

<?php

\get_footer();
