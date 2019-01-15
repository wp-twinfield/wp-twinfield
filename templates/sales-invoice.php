<?php get_header(); ?>

<?php

$header = $twinfield_sales_invoice->get_header();

$date        = $header->get_date();
$due_date    = $header->get_due_date();
$customer    = $header->get_customer();
$header_text = $header->get_header_text();
$footer_text = $header->get_footer_text();

$lines = $twinfield_sales_invoice->get_lines();

?>
<div id="container">
	<div id="content" role="main">
		<div class="page-header">
			<h1>
			<?php

			printf(
				/* translators: %s: Invoice number */
				esc_html__( 'Invoice %s', 'twinfield' ),
				esc_html( $header->get_number() )
			);

			?>
			</h1>
		</div>

		<div class="panel">
			<header>
				<h3><?php esc_html_e( 'Header', 'twinfield' ); ?></h3>
			</header>

			<div class="content">
				<dl class="dl-horizontal">
					<dt><?php esc_html_e( 'Invoice Number', 'twinfield' ); ?></dt>
					<dd><?php echo esc_html( $header->get_number() ); ?></dd>

					<?php if ( $date ) : ?>

						<dt><?php esc_html_e( 'Invoice Date', 'twinfield' ); ?></dt>
						<dd><?php echo esc_html( date_i18n( 'D j M Y', $date->getTimestamp() ) ); ?></dd>

					<?php endif; ?>

					<?php if ( $due_date ) : ?>

						<dt><?php esc_html_e( 'Due Date', 'twinfield' ); ?></dt>
						<dd><?php echo esc_html( date_i18n( 'D j M Y', $due_date->getTimestamp() ) ); ?></dd>

					<?php endif; ?>

					<dt><?php esc_html_e( 'Office', 'twinfield' ); ?></dt>
					<dd><?php echo esc_html( $header->get_office() ); ?></dd>

					<dt><?php esc_html_e( 'Type', 'twinfield' ); ?></dt>
					<dd><?php echo esc_html( $header->get_type() ); ?></dd>

					<dt><?php esc_html_e( 'Customer', 'twinfield' ); ?></dt>
					<dd>
						<?php

						printf(
							'<a href="%s">%s</a>',
							esc_attr( get_twinfield_customer_link( $customer ) ),
							esc_html( $customer )
						);

						?>
					</dd>

					<dt><?php esc_html_e( 'Status', 'twinfield' ); ?></dt>
					<dd><?php echo esc_html( $header->get_status() ); ?></dd>

					<?php if ( $header_text ) : ?>

						<dt><?php esc_html_e( 'Header Text', 'twinfield' ); ?></dt>
						<dd><?php echo esc_html( $header_text ); ?></dd>

					<?php endif; ?>

					<?php if ( $footer_text ) : ?>

						<dt><?php esc_html_e( 'Footer Text', 'twinfield' ); ?></dt>
						<dd><?php echo esc_html( $footer_text ); ?></dd>

					<?php endif; ?>
				</dl>
			</div>
		</div>

		<div class="panel">
			<header>
				<h3><?php esc_html_e( 'Lines', 'twinfield' ); ?></h3>
			</header>

			<table class="table table-striped table-bordered table-condensed">
				<thead>
					<tr>
						<th scope="col"><?php esc_html_e( 'Id', 'twinfield' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Article', 'twinfield' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Sub article', 'twinfield' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Quantity', 'twinfield' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Units', 'twinfield' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Description', 'twinfield' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Value Excl', 'twinfield' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Vat Value', 'twinfield' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Value Inc', 'twinfield' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Free Text 1', 'twinfield' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Free Text 2', 'twinfield' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Free Text 3', 'twinfield' ); ?></th>
					</tr>
				</thead>

				<tbody>

					<?php foreach ( $lines as $line ) : ?>

						<tr>
							<td><?php echo esc_html( $line->get_id() ); ?></td>
							<td><?php echo esc_html( $line->get_article() ); ?></td>
							<td><?php echo esc_html( $line->get_subarticle() ); ?></td>
							<td><?php echo esc_html( $line->get_quantity() ); ?></td>
							<td><?php echo esc_html( $line->get_units() ); ?></td>
							<td><?php echo esc_html( $line->get_description() ); ?></td>
							<td><?php echo esc_html( twinfield_price( $line->get_value_excl() ) ); ?></td>
							<td><?php echo esc_html( twinfield_price( $line->get_vat_value() ) ); ?></td>
							<td><?php echo esc_html( twinfield_price( $line->get_value_inc() ) ); ?></td>
							<td><?php echo esc_html( $line->get_free_text_1() ); ?></td>
							<td><?php echo esc_html( $line->get_free_text_2() ); ?></td>
							<td><?php echo esc_html( $line->get_free_text_3() ); ?></td>
						</tr>

					<?php endforeach; ?>

				</tbody>
			</table>
		</div>

		<div class="panel">
			<header>
				<h3><?php esc_html_e( 'Totals', 'twinfield' ); ?></h3>
			</header>

			<div class="content">
				<dl class="dl-horizontal">
					<dt><?php esc_html_e( 'Value Excl', 'twinfield' ); ?></dt>
					<dd><?php echo esc_html( twinfield_price( $twinfield_sales_invoice->get_value_excl() ) ); ?></dd>

					<dt><?php esc_html_e( 'Vat Value', 'twinfield' ); ?></dt>
					<dd><?php echo esc_html( twinfield_price( $twinfield_sales_invoice->get_vat_value() ) ); ?></dd>

					<dt><?php esc_html_e( 'Value Inc', 'twinfield' ); ?></dt>
					<dd><?php echo esc_html( twinfield_price( $twinfield_sales_invoice->get_value_inc() ) ); ?></dd>
				</dl>
			</div>
		</div>
	</div>
</div>

<?php get_footer(); ?>
