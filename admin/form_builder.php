<?php
$form_builder = new \Pronamic\WP\FormBuilder\FormBuilder();

if ( isset( $_GET[ 'twinfield_form' ] ) ) {
	$page_form = $_GET[ 'twinfield_form' ];
} else {
	$page_form = '';
}
?>
<div class="wrap">
	<?php screen_icon(); ?>
	<h2 class="nav-tab-wrapper">
		<a class="nav-tab <?php if ( empty( $page_form ) ): ?> nav-tab-active <?php endif; ?>" href="<?php echo admin_url( 'admin.php?page=twinfield_form_builder' ); ?>"><?php echo get_admin_page_title(); ?></a>
		<?php foreach ( $form_builder->getValidForms() as $type ) : ?>
			<a class="nav-tab <?php echo ( $page_form == $type ? 'nav-tab-active' : '' ); ?>" href="<?php echo twinfield_get_form_action( $type ); ?>"><?php echo ucfirst( $type ); ?></a>
		<?php endforeach; ?>
	</h2>

	<?php $form_builder->create_form(); ?>

	<form action="<?php echo twinfield_get_form_action( 'invoice' ); ?>" method="post">
		<input type="hidden" name="customerID" value="1002"/>
		<input type="hidden" name="lines[1][article]" value="123"/>
		<input type="hidden" name="lines[2][article]" value="456" />
		<?php submit_button( 'test submit' ); ?>
	</form>
</div>
