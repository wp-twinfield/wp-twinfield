<?php global $twinfield_config; ?>
<div class="wrap">
	<?php screen_icon( 'twinfield' ); ?>

	<h2 class="nav-tab-wrapper">
		<a class="nav-tab <?php if ( ! filter_has_var( INPUT_GET, 'tab' ) ) : ?>nav-tab-active<?php endif; ?>" href="<?php echo add_query_arg( array( 'page' => 'twinfield' ), admin_url( 'admin.php' ) ); ?>"><?php _e( 'Twinfield', 'twinfield' ); ?></a>
		<a class="nav-tab <?php if ( 'customer' === filter_input( INPUT_GET, 'tab', FILTER_SANITIZE_STRING ) ) : ?>nav-tab-active<?php endif; ?>" href="<?php echo add_query_arg( array( 'page' => 'twinfield', 'tab' => 'customer' ), admin_url( 'admin.php' ) ); ?>"><?php _e( 'Customer', 'twinfield' ); ?></a>
		<a class="nav-tab <?php if ( 'invoice' === filter_input( INPUT_GET, 'tab', FILTER_SANITIZE_STRING ) ) : ?>nav-tab-active<?php endif; ?>" href="<?php echo add_query_arg( array( 'page' => 'twinfield', 'tab' => 'invoice' ), admin_url( 'admin.php' ) ); ?>"><?php _e( 'Invoice', 'twinfield' ); ?></a>
	</h2>

	<?php // Twinfield Tab ?>
	<?php if ( ! filter_has_var( INPUT_GET, 'tab' ) ) : ?>
		<form action="https://login.twinfield.com/default.aspx" method="post" target="_blank">
			<p><?php _e( 'Below you can be taken directly to the Twinfield site.', 'twinfield' ); ?></p>
			<p>
				<input name="txtUserID" type="hidden" value="<?php echo $twinfield_config->getUsername(); ?>" />
				<input name="txtPassword" type="hidden" value="<?php echo $twinfield_config->getPassword(); ?>" />
				<input name="txtcompanyID" type="hidden" value="<?php echo $twinfield_config->getOrganisation(); ?>" />
				<?php submit_button( __( 'Login', 'twinfield' ), 'primary', 'btnLogin', false ); ?>
			</p>
		</form>
	<?php endif; ?>
	
	<?php // Customer Tab ?>
	<?php if ( 'customer' === filter_input( INPUT_GET, 'tab', FILTER_SANITIZE_STRING ) ) : ?>
		
		<form method="GET">
			<input type="hidden" name="page" value='twinfield' />
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
	
		<?php if ( filter_has_var( INPUT_GET, 'twinfield_customer_id' ) && filter_input( INPUT_GET, 'twinfield_customer_id', FILTER_VALIDATE_INT ) ) : ?>

			<?php

			// Get the customer factory
			$customer_factory = new \Pronamic\Twinfield\Customer\CustomerFactory( $twinfield_config );
			
			// Get the customer for the passed in twinfield customer ID
			$customer = $customer_factory->get( filter_input( INPUT_GET, 'twinfield_customer_id', FILTER_VALIDATE_INT ) );

			?>
			<?php if ( $customer_factory->getResponse()->isSuccessful() ) : ?>
			<h3><?php _e( 'Customer Response', 'twinfield' ); ?></h3>
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
	
	<?php endif; ?>
			
	<?php if ( 'invoice' === filter_input( INPUT_GET, 'tab', FILTER_SANITIZE_STRING ) ) : ?>
		
		<form method="GET">
			<input type="hidden" name="page" value='twinfield' />
			<input type="hidden" name="tab" value="invoice" />
			<h3><?php _e( 'Load Invoice', 'twinfield' ); ?></h3>
			<table class="form-table">
				<tr>
					<th><?php _e( 'Invoice ID', 'twinfield' ); ?></th>
					<td>
						<input type="text" name="twinfield_invoice_id" value="<?php echo filter_input( INPUT_GET, 'twinfield_invoice_id', FILTER_VALIDATE_INT ); ?>"/>
					</td>
				</tr>
			</table>
			<?php submit_button( __( 'Load Invoice', 'twinfield' ), 'primary', null ); ?>
		</form>
	
		<?php if ( filter_has_var( INPUT_GET, 'twinfield_invoice_id' ) && filter_input( INPUT_GET, 'twinfield_invoice_id', FILTER_VALIDATE_INT ) ) : ?>

			<?php

			// Get the customer factory
			$invoice_factory = new \Pronamic\Twinfield\Invoice\InvoiceFactory( $twinfield_config );
			
			// Get the customer for the passed in twinfield customer ID
			$invoice = $invoice_factory->get( 'FACTUUR', filter_input( INPUT_GET, 'twinfield_invoice_id', FILTER_VALIDATE_INT ) );

			?>
			<?php if ( $invoice_factory->getResponse()->isSuccessful() ) : ?>
				<h3><?php printf( __( 'Invoice %d', 'twinfield' ), $invoice->getInvoiceNumber() ); ?></h3>

				<h4><?php _e( 'Header', 'twinfield' ); ?></h4>
				<table class="form-table">
					<tr>
						<th><strong><?php _e( 'Invoice Number', 'twinfield' ); ?></strong></th>
						<td><?php echo $invoice->getInvoiceNumber(); ?></td>
					</tr>
					<?php if ( $invoice_date = $invoice->getInvoiceDate() ) : ?>
						<tr>
							<th><strong><?php _e( 'Invoice Date', 'twinfield' ); ?></strong></th>
							<td><?php echo $invoice_date; ?></td>
						</tr>
					<?php endif; ?>
					<?php if ( $due_date = $invoice->getDueDate() ) : ?>
						<tr>
							<th><strong><?php _e( 'Due Date', 'twinfield' ); ?></strong></th>
							<td><?php echo $due_date; ?></td>
						</tr>
					<?php endif; ?>
					<tr>
						<th><strong><?php _e( 'Office', 'twinfield' ); ?></strong></th>
						<td><?php echo $invoice->getOffice(); ?></td>
					</tr>
					<tr>
						<th><strong><?php _e( 'Type', 'twinfield' ); ?></strong></th>
						<td><?php echo $invoice->getInvoiceType(); ?></td>
					</tr>
					<tr>
						<th><strong><?php _e( 'Customer', 'twinfield' ); ?></strong></th>
						<td>
							<?php 
							
							$customer = $invoice->getCustomer();
							
							printf( '<a href="%s" target="_blank">%s</a>', twinfield_admin_view_customer_link( $customer->getID() ), $customer->getID() ); 
							
							?>
						</td>
					</tr>
					<tr>
						<th><strong><?php _e( 'Status', 'twinfield' ); ?></strong></th>
						<td><?php echo $invoice->getStatus(); ?></td>
					</tr>
				</table>
				
				<h4><?php _e( 'Lines', 'twinfield' ); ?></h4>
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
				
				<?php
				
				$error_messages = $invoice_factory->getResponse()->getErrorMessages();
				
				?>
				
				<?php foreach ( $error_messages as $error_message ) : ?>
					<div class="error">
						<p><?php echo $error_message; ?></p>
					</div>
				<?php endforeach; ?>
				
			<?php endif; ?>

		<?php endif; ?>
	
	<?php endif; ?>
</div>