<div class="wrap">
    <h2><?php echo get_admin_page_title(); ?></h2>

    <div id="dashboard-widgets-wrap">
		<div id="dashboard-widgets" class="metabox-holder columns-2">
			<div id="postbox-container-1" class="postbox-container">
				<div class="postbox">
					<h3 class="hndle"><span><?php _e( 'Quick Login', 'twinfield' ); ?></span></h3>

					<div class="inside">
					    <form action="https://login.twinfield.com/default.aspx" method="post" target="_blank">
					        <p>
					        	<a href="https://login.twinfield.com/" target="_blank">https://login.twinfield.com/</a>

					            <input name="txtUserID" type="hidden" value="<?php form_option( 'twinfield_username' ); ?>" />
					            <input name="txtPassword" type="hidden" value="<?php form_option( 'twinfield_password' ); ?>" />
					            <input name="txtcompanyID" type="hidden" value="<?php form_option( 'twinfield_organisation' ); ?>" />

					            <?php submit_button( __( 'Login', 'twinfield' ), 'primary', 'btnLogin', false ); ?>
					        </p>
					    </form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
