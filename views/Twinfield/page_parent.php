<?php global $twinfield_config; ?>
<div class="wrap">
	<?php screen_icon( 'twinfield' ); ?>

    <h2><?php echo get_admin_page_title(); ?></h2>

    <form action="https://login.twinfield.com/default.aspx" method="post" target="_blank">
        <p><?php _e( 'Below you can be taken directly to the Twinfield site.', 'twinfield' ); ?></p>
        <p>
            <input name="txtUserID" type="hidden" value="<?php echo $twinfield_config->getUsername(); ?>" />
            <input name="txtPassword" type="hidden" value="<?php echo $twinfield_config->getPassword(); ?>" />
            <input name="txtcompanyID" type="hidden" value="<?php echo $twinfield_config->getOrganisation(); ?>" />
            <?php submit_button( __( 'Login', 'twinfield' ), 'primary', 'btnLogin', false ); ?>
        </p>
    </form>
</div>