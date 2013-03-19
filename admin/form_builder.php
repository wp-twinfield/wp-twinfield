<?php $form_builder = new \Pronamic\WP\FormBuilder\FormBuilder(); ?>
<div class="wrap">
	<ul class="twinfield_form_types_list">
		<?php foreach ( $form_builder->get_valid_form_types() as $type => $view ) : ?>
		<li>
			<a href="<?php echo admin_url( 'admin.php?page=twinfield_form_builder&form=' . $type ); ?>"><?php echo ucfirst( $type ); ?></a>
		</li>
		<?php endforeach; ?>
	</ul>
	<?php $form_builder->create_form(); ?>
</div>