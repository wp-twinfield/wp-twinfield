<?php

do_action( 'wp_twinfield_formbuilder_load_forms' );

// Load FormBuilderUI JS Script on this page
wp_enqueue_script( 'FormBuilderUI' );

/**
 * Form Builder UI Page
 *
 * Loads an instance of the FormBuilder and gets the twinfield-form value
 * from the $_GET super global.
 *
 * Will attempt a show_form call with the $current_form from $_GET
 */

// Get the Form Builder
$form_builder = new \Pronamic\WP\Twinfield\FormBuilder\FormBuilder();

// Get the current form
$current_form = filter_input( INPUT_GET, 'twinfield-form', FILTER_SANITIZE_STRING );

?>
<div class="wrap">
	<h2 class="nav-tab-wrapper">
		<a class="nav-tab <?php if ( empty( $current_form ) ): ?> nav-tab-active <?php endif; ?>" href="<?php echo admin_url( 'admin.php?page=twinfield-form-builder' ); ?>"><?php echo get_admin_page_title(); ?></a>

		<?php foreach ( \Pronamic\WP\Twinfield\FormBuilder\FormBuilderFactory::get_all_form_names() as $type ) : ?>
			<a class="nav-tab <?php echo ( $current_form == $type ? 'nav-tab-active' : '' ); ?>" href="<?php echo twinfield_get_form_action( $type ); ?>"><?php echo ucfirst( $type ); ?></a>
		<?php endforeach; ?>
	</h2>
	<?php if ( ! empty( $current_form ) ) : ?>
		<?php $form_builder->show_form( $current_form ); ?>
	<?php else : ?>
		<div>
			<form action="<?php echo twinfield_get_form_action( 'invoice' ); ?>" method="post">
				<input type="hidden" name="customerID" value="1002"/>
				<input type="hidden" name="lines[1][active]" value="true"/>
				<input type="hidden" name="lines[1][article]" value="123"/>
				<input type="hidden" name="lines[2][article]" value="456" />
				<input type="hidden" name="lines[2][active]" value="true"/>
				<?php submit_button( 'Test Invoice Submit' ); ?>
			</form>
		</div>
		<div>
			<form action="<?php echo twinfield_get_form_action( 'customer' ); ?>" method="post">
				<input type="hidden" name="id" value="11223"/>
				<input type="hidden" name="addresses[1][field1]" value="Field1"/>
				<input type="hidden" name="addresses[1][postcode]" value="1122AA"/>
				<?php submit_button( 'Test Customer Submit' ); ?>
			</form>
		</div>
	<?php endif;?>
</div>
