<?php wp_twinfield_admin_query_nav( 'twinfield_customer_id' ); ?>
<?php if ( ! filter_has_var( INPUT_GET, 'twinfield_customer_id' ) ) : ?>
<form method="GET">
    <input type="hidden" name="page" value='twinfield-query' />
    <input type="hidden" name="tab" value="customer" />
    <h3><?php _e( 'Load Customer', 'twinfield' ); ?></h3>
    <table class="form-table">
        <tr>
            <th><?php _e( 'Customer ID', 'twinfield' ); ?></th>
            <td>
                <input type="text" name="twinfield_customer_id" value="<?php echo filter_input( INPUT_GET, 'twinfield_customer_id', FILTER_VALIDATE_INT ); ?>"/>
            </td>
        </tr>
    </table>
    <?php submit_button( __( 'Load Customer', 'twinfield' ), 'primary', null ); ?>
</form>
<?php endif; ?>

<?php if ( filter_has_var( INPUT_GET, 'twinfield_customer_id' ) && filter_input( INPUT_GET, 'twinfield_customer_id', FILTER_VALIDATE_INT ) ) : ?>

    <?php
    
    global $twinfield_config;

    // Get the customer factory
    $customer_factory = new \Pronamic\Twinfield\Customer\CustomerFactory( $twinfield_config );

    // Get the customer for the passed in twinfield customer ID
    $customer = $customer_factory->get( filter_input( INPUT_GET, 'twinfield_customer_id', FILTER_VALIDATE_INT ) );

    ?>
    <?php if ( $customer_factory->getResponse()->isSuccessful() ) : ?>
    <h2><?php printf( __( 'Customer %s', 'twinfield' ), $customer->getID() ); ?></h2>
    <table class="form-table">
            <tr>
                <th><strong><?php _e( 'Name', 'twinfield' ); ?></strong></th>
                <td><?php echo $customer->getName(); ?></td>
            </tr>
            <tr>
                <th><strong><?php _e( 'Website', 'twinfield' ); ?></strong></th>
                <td><?php echo $customer->getWebsite(); ?></td>
            </tr>
            <tr>
                <th><strong><?php _e( 'Addresses', 'twinfield' ); ?></strong></th>
                <td>
                    <table class="widefat">
                        <thead>
                            <tr>
                                <th><?php _e( 'Name', 'twinfield' ); ?></th>
                                <th><?php _e( 'City', 'twinfield' ); ?></th>
                                <th><?php _e( 'Postal Code', 'twinfield' ); ?></th>
                                <th><?php _e( 'Telephone', 'twinfield' ); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ( $customer->getAddresses() as $address ) : ?>
                                <tr>
                                    <td><?php echo $address->getName(); ?></td>
                                    <td><?php echo $address->getCity(); ?></td>
                                    <td><?php echo $address->getPostcode(); ?></td>
                                    <td><?php echo $address->getTelephone(); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </td>
            </tr>
    </table>
    <?php else: ?>

    <?php

        $error_messages = $customer_factory->getResponse()->getErrorMessages();

        ?>

        <?php foreach ( $error_messages as $error_message ) : ?>
            <div class="error">
                <p><?php echo $error_message; ?></p>
            </div>
        <?php endforeach; ?>

    <?php endif; ?>

<?php endif; ?>