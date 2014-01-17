<div class="wrap">
	<?php screen_icon( 'twinfield' ); ?>

	<h2><?php echo get_admin_page_title(); ?></h2>

	<form method="post" action="options.php">
		<?php settings_fields( \Pronamic\WP\Twinfield\Settings\Settings::active_tab_group() ); ?>

		<?php do_settings_sections( 'twinfield_settings' ); ?>

		<?php submit_button(); ?>
	</form>
</div>