<?php get_header(); ?>

<div id="container">
	<div id="content" role="main">

		<div class="page-header">
			<h1><?php printf( __( 'Invoice %s', 'twinfield' ), $twinfield_invoice->getInvoiceNumber() ); ?></h1>
		</div>

		<div class="panel">
			<header>
				<h3><?php _e( 'Header', 'twinfield' ); ?></h3>
			</header>

			<div class="content">
				<dl class="dl-horizontal">
					<dt><?php _e( 'Invoice Number', 'twinfield' ); ?></dt>
					<dd><?php echo $twinfield_invoice->getInvoiceNumber(); ?></dd>

					<?php if ( $invoice_date = $twinfield_invoice->getInvoiceDate() ): ?>

						<dt><?php _e( 'Invoice Date', 'twinfield' ); ?></dt>
						<dd><?php echo $invoice_date; ?></dd>

					<?php endif; ?>

					<?php if ( $due_date = $twinfield_invoice->getDueDate() ): ?>

						<dt><?php _e( 'Due Date', 'twinfield' ); ?></dt>
						<dd><?php echo $due_date; ?></dd>

					<?php endif; ?>


					<dt><?php _e( 'Office', 'twinfield' ); ?></dt>
					<dd><?php echo $twinfield_invoice->getOffice(); ?></dd>

					<dt><?php _e( 'Type', 'twinfield' ); ?></dt>
					<dd><?php echo $twinfield_invoice->getInvoiceType(); ?></dd>

					<dt><?php _e( 'Customer', 'twinfield' ); ?></dt>
					<dd>
						<?php

						$customer = $twinfield_invoice->getCustomer();

						printf(
							'<a href="%s" target="_blank">%s</a>',
							site_url( '/debiteuren/' . $customer->getID() . '/' ),
							$customer->getID()
						);

						?>
					</dd>

					<dt><?php _e( 'Status', 'twinfield' ); ?></dt>
					<dd><?php echo $twinfield_invoice->getStatus(); ?></dd>
				</dl>
			</div>
		</div>

		<div class="panel">
			<header>
				<h3><?php _e( 'Lines', 'twinfield' ); ?></h3>
			</header>

			<?php $lines = $twinfield_invoice->getLines(); ?>

			<table class="table table-striped table-bordered table-condensed">
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
		</div>
	</div>
</div>

<?php get_footer(); ?>
