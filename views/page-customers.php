<?php

namespace Pronamic\WP\Twinfield;

$user         = get_option( 'twinfield_username' );
$password     = get_option( 'twinfield_password' );
$organisation = get_option( 'twinfield_organisation' );
$office       = get_option( 'twinfield_default_office_code' );

$credentials = new Credentials( $user, $password, $organisation );

$client = new Client();

$logon_response = $client->logon( $credentials );

$session = $client->get_session( $logon_response );

$xml_processor = new XMLProcessor( $session );

$service = new Customers\CustomerService( $xml_processor );

$customer = $service->get_customer( $office, filter_input( INPUT_GET, 'twinfield_customer_id', FILTER_SANITIZE_STRING ) );

var_dump( $customer );

if ( filter_has_var( INPUT_GET, 'twinfield_customer_id' ) ) {
	global $twinfield_config;

	$customer_factory = new \Pronamic\Twinfield\Customer\CustomerFactory( $twinfield_config );

	$customer = $customer_factory->get(
		filter_input( INPUT_GET, 'twinfield_customer_id', FILTER_VALIDATE_INT )
	);

	$response = $customer_factory->getResponse();

	if ( ! $response ) {
		$error_messages = array(
			__( 'Could not connect to Twinfield.', 'twinfield' ),
		);
	} elseif ( ! $response->isSuccessful() ) {
		$error_messages = $response->getErrorMessages();
	}
} else {
	$customer = false;
}

?>
<div class="wrap">
	<h2><?php echo get_admin_page_title(); ?></h2>

	<?php if ( ! filter_has_var( INPUT_GET, 'twinfield_customer_id' ) || isset( $error_messages ) ) : ?>

		<form method="get" action="">
			<input type="hidden" name="page" value="twinfield_customers" />

			<h3><?php _e( 'Request Customer', 'twinfield' ); ?></h3>

			<table class="form-table">
				<tr>
					<th><?php _e( 'Customer ID', 'twinfield' ); ?></th>
					<td>
						<input type="text" name="twinfield_customer_id" value="<?php echo filter_input( INPUT_GET, 'twinfield_customer_id', FILTER_VALIDATE_INT ); ?>" />
					</td>
				</tr>
			</table>

			<?php submit_button( __( 'Request', 'twinfield' ), 'primary', null ); ?>
	    </form>

	<?php endif; ?>

	<?php if ( ! isset( $error_messages ) && false !== $customer ) : ?>

		<h3><?php printf( __( 'Customer %s', 'twinfield' ), $customer->getID() ); ?></h3>

		<table class="widefat">
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

	<?php else : ?>

		<?php if ( ! empty( $error_messages ) ) : ?>
			<?php foreach ( $error_messages as $error_message ) : ?>
				<div class="error">
					<p><?php echo $error_message; ?></p>
				</div>
			<?php endforeach; ?>
		<?php endif; ?>

	<?php endif; ?>

</div>
