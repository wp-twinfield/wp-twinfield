<div class="wrap">
	<?php screen_icon( 'twinfield' ); ?>

	<h2><?php echo get_admin_page_title(); ?></h2>

	<?php if ( ! filter_has_var( INPUT_GET, 'twinfield_invoice_id' )  || isset( $error_messages ) ) : ?>
	
		<form method="get">
			<input type="hidden" name="page" value="twinfield_invoices" />

			<h3><?php _e( 'Search for invoices', 'twinfield' ); ?></h3>

			<table class="form-table">
				<tr>
					<th><?php _e( 'Invoice ID', 'twinfield' ); ?></th>
					<td>
						<input type="text" name="twinfield_invoice_id" value="<?php echo filter_input( INPUT_GET, 'twinfield_invoice_id', FILTER_VALIDATE_INT ); ?>" />
					</td>
				</tr>
			</table>

			<?php submit_button( __( 'Search', 'twinfield' ), 'primary', null ); ?>
		</form>

	<?php endif; ?>

	<?php if ( ! isset( $error_messages ) && false !== $invoice ) : ?>
	
		<h3><?php printf( __( 'Invoice %d', 'twinfield' ), $invoice->getInvoiceNumber() ); ?></h3>
	
		<h4><?php _e( 'Header', 'twinfield' ); ?></h4>

		<table class="form-table">
			<tr>
				<th>
					<strong><?php _e( 'Invoice Number', 'twinfield' ); ?></strong>
				</th>
				<td><?php echo $invoice->getInvoiceNumber(); ?></td>
			</tr>

			<?php if ( $invoice_date = $invoice->getInvoiceDate() ) : ?>

				<tr>
					<th>
						<strong><?php _e( 'Invoice Date', 'twinfield' ); ?></strong>
					</th>
					<td><?php echo $invoice_date; ?></td>
				</tr>

			<?php endif; ?>

			<?php if ( $due_date = $invoice->getDueDate() ) : ?>

				<tr>
					<th>
						<strong><?php _e( 'Due Date', 'twinfield' ); ?></strong>
					</th>
					<td><?php echo $due_date; ?></td>
				</tr>

			<?php endif; ?>

			<tr>
				<th>
					<strong><?php _e( 'Office', 'twinfield' ); ?></strong>
				</th>
				<td><?php echo $invoice->getOffice(); ?></td>
			</tr>
			<tr>
				<th>
					<strong><?php _e( 'Type', 'twinfield' ); ?></strong>
				</th>
				<td><?php echo $invoice->getInvoiceType(); ?></td>
			</tr>
			<tr>
				<th>
					<strong><?php _e( 'Customer', 'twinfield' ); ?></strong>
				</th>
				<td>
					<?php 

					$customer = $invoice->getCustomer();
	
					printf( '<a href="%s" target="_blank">%s</a>', twinfield_admin_view_customer_link( $customer->getID() ), $customer->getID() );
	
					?>
				</td>
			</tr>
			<tr>
				<th>
					<strong><?php _e( 'Status', 'twinfield' ); ?></strong>
				</th>
				<td>
					<?php echo $invoice->getStatus(); ?>
				</td>
			</tr>
		</table>
	
		<h4>
			<?php _e( 'Lines', 'twinfield' ); ?>
		</h4>

		<?php $lines = $invoice->getLines(); ?>
	
		<table class="widefat">
			<thead>
				<tr>
					<th scope="col"><?php _e( 'Id', 'twinfield' ); ?></th>
					<th scope="col"><?php _e( 'Article', 'twinfield' ); ?></th>
					<th scope="col"><?php _e( 'Sub article', 'twinfield' ); ?></th>
					<th scope="col"><?php _e( 'Quantity', 'twinfield' ); ?></th>
					<th scope="col"><?php _e( 'Units', 'twinfield' ); ?></th>
					<th scope="col"><?php _e( 'Description', 'twinfield' ); ?></th>
					<th scope="col"><?php _e( 'Value Excl', 'twinfield' ); ?></th>
					<th scope="col"><?php _e( 'Vat Value', 'twinfield' ); ?></th>
					<th scope="col"><?php _e( 'Value Inc', 'twinfield' ); ?></th>
					<th scope="col"><?php _e( 'Units Price Excl', 'twinfield' ); ?></th>
					<th scope="col"><?php _e( 'Free text 1', 'twinfield' ); ?></th>
				</tr>
			</thead>
	
			<tbody>
	
				<?php foreach ( $lines as $line ) : ?>
		
					<tr>
						<td><?php echo $line->getID(); ?></td>
						<td><?php echo $line->getArticle(); ?></td>
						<td><?php echo $line->getSubArticle(); ?></td>
						<td><?php echo $line->getQuantity(); ?></td>
						<td><?php echo $line->getUnits(); ?></td>
						<td><?php echo $line->getDescription(); ?></td>
						<td><?php echo twinfield_price( $line->getValueExcl() ); ?></td>
						<td><?php echo twinfield_price( $line->getVatValue() ); ?></td>
						<td><?php echo twinfield_price( $line->getValueInc() ); ?></td>
						<td><?php echo twinfield_price( $line->getUnitsPriceExcl() ); ?></td>
						<td><?php echo $line->getFreeText1(); ?></td>
					</tr>
	
				<?php endforeach; ?>
	
			</tbody>
		</table>

	<?php else: ?>

		<?php if ( ! empty( $error_messages ) ) : ?>
			<?php foreach ( $error_messages as $error_message ) : ?>
				<div class="error">
					<p>
						<?php echo $error_message; ?>
					</p>
				</div>
			<?php endforeach; ?>
		<?php endif; ?>

	<?php endif; ?>
</div>