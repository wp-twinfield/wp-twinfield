<?php get_header(); ?>

<div id="container">
	<div id="content" role="main">


		<dl>
			<dt>Header</dt>
			<dd>

				<dl>
					<dt><?php _e( 'Invoice Number', 'twinfield' ); ?></dt>
					<dd><?php echo $invoice->getInvoiceNumber(); ?></dd>

					<?php if ( $invoice_date = $invoice->getInvoiceDate() ): ?>

						<dt><?php _e( 'Invoice Date', 'twinfield' ); ?></dt>
						<dd><?php echo $invoice_date->format( 'd-m-Y' ); ?></dd>

					<?php endif; ?>

					<?php if ( $due_date = $invoice->getDueDate() ): ?>

						<dt><?php _e( 'Due Date', 'twinfield' ); ?></dt>
						<dd><?php echo $due_date->format( 'd-m-Y' ); ?></dd>

					<?php endif; ?>


					<dt><?php _e( 'Office', 'twinfield' ); ?></dt>
					<dd><?php echo $invoice->getOffice(); ?></dd>

					<dt><?php _e( 'Type', 'twinfield' ); ?></dt>
					<dd><?php echo $invoice->getInvoiceType(); ?></dd>

					<dt><?php _e( 'Customer', 'twinfield' ); ?></dt>
					<dd>
						<?php

						$customer = $invoice->getCustomer();

						printf(
							'<a href="%s" target="_blank">%s</a>',
							site_url( '/debiteuren/' . $customer->getID() . '/' ),
							$customer->getID()
						);

						?>
					</dd>

					<dt><?php _e( 'Status', 'twinfield' ); ?></dt>
					<dd><?php echo $invoice->getStatus(); ?></dd>
				</dl>
			</dd>

			<dt>Lines</dt>
			<dd>
				<?php $lines = $invoice->getLines(); ?>

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
							<td><?php echo $line->getID(); ?></td>
							<td><?php echo $line->getArticle(); ?></td>
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

	</div>
</div>

<?php get_footer(); ?>