<div class="wrap">
	<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

	<form method="post" action="options.php">
		<?php settings_fields( 'twinfield' ); ?>

		<?php do_settings_sections( 'twinfield' ); ?>

		<?php submit_button(); ?>
	</form>
</div>
