<div class="wrap">
	<?php screen_icon('twinfield'); ?>

	<h2>
		<?php echo esc_html('Twinfield'); ?>
	</h2>

	<form method="post" action="options.php">
		<?php settings_fields('twinfield'); ?>

		<h3 class="title">API</h3>

		<table class="form-table">
			<tr valign="top">
				<th scope="row">
					<label for="username-field"><?php _e('Username', Twinfield::TEXT_DOMAIN); ?></label>
				</th>
				<td>
					<input id="username-field" name="twinfield-username" value="<?php echo self::decrypt(get_option('twinfield-username')); ?>" type="text" class="regular-text" />
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">
					<label for="password-field"><?php _e('Password', Twinfield::TEXT_DOMAIN); ?></label>
				</th>
				<td>
					<input id="password-field" name="twinfield-password" value="<?php echo self::decrypt(get_option('twinfield-password')); ?>" type="password" class="regular-text" />
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">
					<label for="organisation-field"><?php _e('Organisation', Twinfield::TEXT_DOMAIN); ?></label>
				</th>
				<td>
					<input id="organisation-field" name="twinfield-organisation" value="<?php echo self::decrypt(get_option('twinfield-organisation')); ?>" type="text" class="regular-text" />
				</td>
			</tr>
		</table>

		<?php submit_button(); ?>
	</form>
</div>