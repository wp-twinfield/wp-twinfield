<div class="wrap">
	<?php screen_icon( 'twinfield' ); ?>

	<h2 class="nav-tab-wrapper">
		<?php \Pronamic\WP\Twinfield\Settings\Settings::tab_html( 'api', true ); ?>
		<?php \Pronamic\WP\Twinfield\Settings\Settings::tab_html( 'permalinks' ); ?>
	</h2>

	<form method="post" action="options.php">
		<?php settings_fields( \Pronamic\WP\Twinfield\Settings\Settings::active_tab_group() ); ?>
		<table class="form-table">
			<?php do_settings_fields( 'twinfield-settings', \Pronamic\WP\Twinfield\Settings\Settings::active_tab() ); ?>
		</table>

		<?php submit_button(); ?>
	</form>
</div>