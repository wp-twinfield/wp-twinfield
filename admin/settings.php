<div class="wrap">
	<?php screen_icon( 'twinfield' ); ?>

	<h2>
		<?php echo esc_html( 'Twinfield' ); ?>
	</h2>

	<form method="post" action="options.php">
		<?php settings_fields( 'twinfield' ); ?>

		<h3 class="title">API</h3>

		<table class="form-table">
			<tr valign="top">
				<th scope="row">
					<label for="username-field"><?php _e( 'Username', 'twinfield' ); ?></label>
				</th>
				<td>
					<input id="username-field" name="twinfield_username" value="<?php form_option( 'twinfield_username' ); ?>" type="text" class="regular-text" />
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">
					<label for="password-field"><?php _e( 'Password', 'twinfield' ); ?></label>
				</th>
				<td>
					<input id="password-field" name="twinfield_password" value="<?php form_option( 'twinfield_password' ); ?>" type="password" class="regular-text" />
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">
					<label for="organisation-field"><?php _e('Organisation', 'twinfield' ); ?></label>
				</th>
				<td>
					<input id="organisation-field" name="twinfield_organisation" value="<?php form_option( 'twinfield_organisation' ); ?>" type="text" class="regular-text" />
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">
					<label for="office-field"><?php _e('Office Code', 'twinfield' ); ?></label>
				</th>
				<td>
					<input id="office-field" name="twinfield_office_code" value="<?php form_option( 'twinfield_office_code' ); ?>" type="text" class="regular-text" />
				</td>
			</tr>
		</table>

		<?php submit_button(); ?>
	</form>
</div>