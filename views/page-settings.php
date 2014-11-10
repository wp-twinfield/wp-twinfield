<div class="wrap">
	<h2><?php echo get_admin_page_title(); ?></h2>

	<form method="post" action="options.php">
		<?php settings_fields( 'twinfield'  ); ?>

		<?php do_settings_sections( 'twinfield' ); ?>

		<?php submit_button(); ?>
	</form>
</div>