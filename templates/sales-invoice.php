<?php get_header(); ?>

<div id="container">
	<div id="content" role="main">
		<?php 
		
		global $twinfieldSalesInvoice; 

		$salesInvoice = $twinfieldSalesInvoice;
		
		if($salesInvoice): ?>
		
		<dl>
			<dt>Header</dt>
			<dd>
				<?php $header = $salesInvoice->getHeader(); ?>

				<dl>
					<dt><?php _e( 'Invoice Number', 'twinfield' ); ?></dt>
					<dd><?php echo $header->getInvoiceNumber(); ?></dd>
		
					<?php if ( $invoice_date = $header->getInvoiceDate() ): ?>

						<dt><?php _e( 'Invoice Date', 'twinfield' ); ?></dt>
						<dd><?php echo $invoice_date->format( 'd-m-Y' ); ?></dd>

					<?php endif; ?>
		
					<?php if ( $due_date = $header->getDueDate() ): ?>

						<dt><?php _e( 'Due Date', 'twinfield' ); ?></dt>
						<dd><?php echo $due_date->format( 'd-m-Y' ); ?></dd>

					<?php endif; ?>
					

					<dt><?php _e( 'Office', 'twinfield' ); ?></dt>
					<dd><?php echo $header->getOffice(); ?></dd>
		
					<dt><?php _e( 'Type', 'twinfield' ); ?></dt>
					<dd><?php echo $header->getType(); ?></dd>
		
					<dt><?php _e( 'Customer', 'twinfield' ); ?></dt>
					<dd>
						<?php 

						$customer = $header->getCustomer();

						printf(
							'<a href="%s" target="_blank">%s</a>',
							site_url( '/debiteuren/' . $customer . '/' ),
							$customer
						);

						?>
					</dd>
		
					<dt><?php _e( 'Status', 'twinfield' ); ?></dt>
					<dd><?php echo $header->getStatus(); ?></dd>
				</dl>
			</dd>
		
			<dt>Lines</dt>
			<dd>
				<?php $lines = $salesInvoice->getLines(); ?>
				
				<table class="table table-striped table-bordered table-condensed">
					<thead>
						<tr>
							<th scope="col">Id</th>
							<th scope="col">Article</th>
							<th scope="col">Sub article</th>
							<th scope="col">Quantity</th>
							<th scope="col">Units</th>
							<th scope="col">Allow discount or premium</th>
							<th scope="col">Description</th>
							<th scope="col">Value Excl</th>
							<th scope="col">Vat Value</th>
							<th scope="col">Value Inc</th>
							<th scope="col">Units Price Excl</th>
							<th scope="col">Free text 1</th>
						</tr>
					</thead>
		
					<tbody>
		
						<?php foreach($lines as $line): ?>
		
						<tr>
							<td><?php echo $line->id; ?></td>
							<td><?php echo $line->article; ?></td>
							<td><?php echo $line->subArticle; ?></td>
							<td><?php echo $line->quantity; ?></td>
							<td><?php echo $line->units; ?></td>
							<td><?php echo $line->allowDiscountOrPremium ? 'yes' : 'no'; ?></td>
							<td><?php echo $line->description; ?></td>
							<td><?php echo $line->valueExcl; ?></td>
							<td><?php echo $line->vatValue; ?></td>
							<td><?php echo $line->valueInc; ?></td>
							<td><?php echo $line->unitsPriceExcl; ?></td>
							<td><?php echo $line->freeText1; ?></td>
						</tr>
		
						<?php endforeach; ?>
		
					</tbody>
				</table>
			</dd>
		</dl>
		
		<?php endif; ?>
		
	</div>
</div>

<?php get_footer(); ?>