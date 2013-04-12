<?php
$merger = new \Pronamic\WP\Merge\MergeFinder();

$table = '';
if ( isset( $_GET[ 'twinfield-table' ] ) ) {
	$table = $_GET[ 'twinfield-table' ];
}
?>
<div class="wrap">
	<?php screen_icon(); ?>
	<h2 class="nav-tab-wrapper">
		<a class="nav-tab <?php if ( empty( $table ) ) : ?> nav-tab-active <?php endif; ?>" href="<?php echo admin_url( 'admin.php?page=twinfield-merger' ); ?>"><?php echo get_admin_page_title(); ?></a>
		<?php foreach ( $merger->get_valid_supports() as $support ) : ?>
			<a class="nav-tab <?php echo ( $table == $support ? 'nav-tab-active' : '' ); ?>" href="<?php echo twinfield_get_merger_table_action( $support ); ?>"><?php echo ucfirst( $support ); ?></a>
		<?php endforeach; ?>
	</h2>
	<?php if ( ! empty( $table ) ) : ?>
		<form method="GET">
			<input type="hidden" name="page" value="twinfield-merger">
			<input type="hidden" name="twinfield-table" value="<?php echo $table; ?>">
			<table class="form-table">
				<tr>
					<th><?php _e( 'Response Limit', 'twinfield' ); ?></th>
					<td><input type="text" name="limit" value="<?php echo isset( $_GET[ 'limit' ] ) ? $_GET[ 'limit' ] : ''; ?>"/></td>
				</tr>
				<tr>
					<th><?php _e( 'Response Offset', 'twinfield' ); ?></th>
					<td><input type="text" name="offset" value="<?php echo isset( $_GET[ 'offset' ] ) ? $_GET[ 'offset' ] : ''; ?>"/></td>
				</tr>
				<tr>
					<th><?php _e( 'Custom Meta Field', 'twinfield' ); ?></th>
					<td><input type="text" name="current_field" value="<?php echo isset( $_GET[ 'current_field' ] ) ? $_GET[ 'current_field' ] : ''; ?>"/></td>
				</tr>
				<tr>
					<th><?php _e( 'New Custom Field', 'twinfield' ); ?></th>
					<td><input type="text" name="new_field" value="<?php echo isset( $_GET[ 'new_field' ] ) ? $_GET[ 'new_field' ] : ''; ?>"/></td>
				</tr>
			</table>
			<?php submit_button( __( 'Show', 'twinfield' ), 'primary', false ); ?>
		</form>
		<form method="POST">
			<input type="hidden" name="action" value="merger_automate" />
			<?php submit_button( __( 'Automate', 'twinfield' ), 'secondary', false ); ?>
		</form>
		<hr/>
		<?php if ( ! empty( $_GET[ 'limit' ] ) ) : ?>
			<?php $merger->create_response(); ?>
		<?php endif; ?>
	<?php endif; ?>
</div>