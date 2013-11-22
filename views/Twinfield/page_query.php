<?php if ( null === $tab ) : ?>
<?php wp_twinfield_admin_query_nav(); ?>
<?php endif; ?>
<?php if( $tab === 'customer' ) : ?>
<?php include( PRONAMIC_TWINFIELD_FOLDER . '/views/Pronamic/WP/Customer/render_customer_admin.php' ); ?>
<?php endif; ?>
<?php if ( $tab === 'invoice' ) : ?>
<?php include( PRONAMIC_TWINFIELD_FOLDER . '/views/Pronamic/WP/Invoice/render_invoice_admin.php' ); ?>
<?php endif; ?>
