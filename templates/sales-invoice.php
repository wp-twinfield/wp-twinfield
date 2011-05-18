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
					<dt>Invoice Number</dt>
					<dd><?php echo $header->getInvoiceNumber(); ?></dd>
		
					<dt>Office</dt>
					<dd><?php echo $header->getOffice(); ?></dd>
		
					<dt>Type</dt>
					<dd><?php echo $header->getType(); ?></dd>
				</dl>
			</dd>
		
			<dt>Lines</dt>
			<dd>
				<?php $lines = $salesInvoice->getLines(); ?>
				
				<table>
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