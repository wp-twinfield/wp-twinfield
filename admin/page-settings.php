<?php

// @codingStandardsIgnoreStart

?>
<script src="https://unpkg.com/select2@4.0.6-rc.1/dist/js/select2.full.min.js" type="text/javascript"></script>

<link rel="stylesheet" href="https://unpkg.com/select2@4.0.6-rc.1/dist/css/select2.min.css" type="text/css" media="all" />

<script type="text/javascript">
	jQuery( document ).ready( function( $ ) {
		var url = 'http://twinfield.test/wp-json/twinfield/v1/offices';

		$elements = $( '#twinfield_default_office_code' );

		$.getJSON( url, function( offices ) {
			if ( ! offices ) {
				return;
			}

			$elements.each( function() {
				$element = $( this );

				var current = $element.val();

				var select2options = offices;

				$.map( select2options, function( obj ) {
					obj.id       = obj.code;
					obj.text     = obj.name;
					obj.selected = ( current == obj.code );
				} );

				select2options.unshift( {
					id: '',
					text: ''
				} );

				$elements.select2( {
					allowClear: true,
					placeholder: {
						id: '',
						text: 'Select an option'
					},
					data: select2options,
				} );
			} );
		} );
	} );
</script>

<?php

// @codingStandardsIgnoreEnd

?>

<div class="wrap">
	<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

	<form method="post" action="options.php">
		<?php settings_fields( 'twinfield' ); ?>

		<?php do_settings_sections( 'twinfield' ); ?>

		<?php submit_button(); ?>
	</form>
</div>
