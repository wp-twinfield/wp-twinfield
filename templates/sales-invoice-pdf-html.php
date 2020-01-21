<?php

/**
 * mPDF template.
 *
 * @link https://mpdf.github.io/css-stylesheets/supported-css.html
 */

global $twinfield_sales_invoice;
global $twinfield_customer;

$header = $twinfield_sales_invoice->get_header();

$lines = $twinfield_sales_invoice->get_lines();

$address = $twinfield_customer->get_address_by_number( $header->get_invoice_address_number() );

$rows = array();

if ( null !== $address ) {
	$rows[] = $address->get_name();
	$rows[] = $address->get_field_1();
	$rows[] = $address->get_field_2();
	$rows[] = $address->get_field_3();
	$rows[] = sprintf(
		'%s %s',
		$address->get_postcode(),
		$address->get_city()
	);

	$country = $address->get_country();

	if ( null !== $country ) {
		$rows[] = $country->get_name();
	}
}

$rows = array_map( 'trim', $rows );

$rows = array_filter( $rows );

?>
<htmlpagefooter name="PronamicFooter">
	<div class="pronamic-footer">
		Pronamic | KvK: 01108446 | BTW: NL8143.27.394.B01 | IBAN: NL56 RABO 0108 6347 79 | BIC: RABONL2U | info@pronamic.nl
	</div>
</htmlpagefooter>

<sethtmlpagefooter name="PronamicFooter" value="on" />

<style type="text/css">
	body {
		font-family: tahoma;
		font-size: 10pt;
	}

	table {
		border-collapse: collapse;
	}

	table th,
	table td {
		padding: 3px 0;
	}

	.pronamic-title {
		color: #F9461C;

		font-size: 14pt;
		font-weight: normal;
	}

	.pronamic-invoice-lines-table th,
	.pronamic-invoice-lines-table td {
		padding: 5px 0;
	}

	.pronamic-footer {
		font-size: 8pt;

		text-align: center;
	}
</style>

<div style="float: left; width: 50%; padding-top: 3em;">
	<h1 class="pronamic-title">Factuur</h1>

	<br />

	<div>
		<?php echo implode( '<br />', $rows ); ?>
	</div>
</div>

<div style="float: right; width: 50%; font-size: 8pt; text-align: right;">
	<svg viewBox="0 0 278.6 72.4">
		<path class="pt-logo-sign" fill="#f9461c" d="M268.9 16.2c2.9-.1 4.6-1.4 4.6-3.6 0-3.6-4-5.6-11.7-5.9h-.6V11l2.8.2V9.6c4 .3 6.8 1.4 6.8 2.8 0 1-1.3 1.2-2.4 1.2-1.9 0-3.1-.1-4.5-.2-.6-.1-1.3-.1-2.1-.2h-.6v8.7c-3.3-1.6-5.6-5.1-5.6-9 0-5.5 4.5-10 10-10s10 4.5 10 10c0 4.9-3.5 8.9-8 9.8v2.9c6.2-1 10.9-6.3 10.9-12.8 0-7.1-5.8-12.9-12.9-12.9-7.1 0-12.9 5.8-12.9 12.9 0 6.6 4.9 12 11.3 12.8v-9.5c.7.1 2 .2 4.9.1"></path>

		<g>
			<path class="pt-logo-text" fill="#2c3341" d="M7.4 72.4H0V37.8c0-5.3 2.6-7.5 6.2-8.2 2-.4 4.8-.4 8-.4 3.1 0 5.7 0 7.1.3 4.1.7 6.7 3.8 6.9 8.4.1 2.9.1 5.5.1 7.8 0 2.7 0 4.8-.1 7.2-.1 5.8-3.3 9.2-11.6 9.2H7.4v10.3zm10.5-37.1c-1.2-.2-2.6-.2-3.9-.2-1.3 0-3.1 0-4.2.2-2.3.5-2.4 2.7-2.4 4.2v12.9c0 2 1.1 3.3 3.2 3.5 1.3.1 2.2.1 3.9.1 2.1 0 3 0 3.9-.2 1.4-.4 2.3-1.4 2.4-2.8.1-2.7.1-4.9.1-7.4 0-2.1 0-4.1-.1-6.9-.1-1.8-.9-3.1-2.9-3.4zM34.6 37.7c0-5 3-7.5 7.1-8.2 1.5-.3 4.1-.3 7.2-.3h6.2v6H49c-1.6 0-2.6 0-3.9.1-2 .2-3.2 1.5-3.2 3.3v23.5h-7.4V37.7zM63.8 61.5c-3.2-1-5.7-3.1-6.2-6.9-.1-1.3-.1-4.6-.1-9 0-4.5 0-7.7.1-9 .4-3.8 3-5.9 6.2-6.9 2-.6 4.6-.6 7.8-.6s6 0 7.9.6c3.2 1 5.7 3.1 6.2 6.9.1 1.3.1 4.5.1 9 0 4.4 0 7.7-.1 9-.4 3.8-3 5.9-6.2 6.9-2 .6-4.6.6-7.9.6-3.1 0-5.8 0-7.8-.6zM76 55.9c1.4-.4 2.2-1.3 2.4-2.6.1-.6.1-4.1.1-7.6s-.1-7-.1-7.6c-.1-1.3-1-2.2-2.4-2.6-.9-.2-2.2-.2-4.3-.2-2.1 0-3.4 0-4.3.2-1.4.3-2.2 1.2-2.4 2.5-.1.6-.1 4.1-.1 7.6 0 3.6.1 7 .1 7.6.1 1.3 1 2.2 2.4 2.6.9.2 2.2.2 4.3.2 2.1.1 3.4.1 4.3-.1zM102.1 35.4c-1.4.4-2.2 1.3-2.4 2.6-.1.6-.1 1.3-.1 2.1v22h-7.4V40.8c0-1.4 0-2.8.1-4.2.4-3.8 3-5.9 6.2-6.9 2-.6 4.6-.6 7.8-.6 3.1 0 5.8 0 7.8.6 3.2 1 5.7 3.1 6.2 6.9.1 1.3.1 2.8.1 4.2v21.3H113v-22c0-.7 0-1.5-.1-2.1-.1-1.3-1-2.2-2.4-2.6-.9-.2-2.2-.2-4.3-.2s-3.2 0-4.1.2zM143 29.2c7.8 0 10.9 3.5 10.9 9.1v15.3c0 5.9-3.8 8.2-9.9 8.5-1.3.1-2.8.1-4.2.1-1.5 0-2.9 0-4.3-.1-5.5-.2-9.9-2.4-9.9-8.1v-4.1c0-6.3 4.3-9.1 11.3-9.1h7.5c1.1 0 2-.5 2-1.8v-1.2c0-2.3-2.5-2.6-5-2.6h-11.3v-6H143zm3.4 17.6h-7.6c-3 0-5.7.3-5.7 3.6v2.1c0 2.1.8 3.2 3.4 3.5 1 .1 2.4.1 3.6.1 1 0 2.2 0 3.2-.1 2.5-.3 3.3-1.5 3.3-3.3v-5.9zM178.6 62.1V38.9c0-2.4-1.1-3.3-3.2-3.6-.8-.1-1.8-.1-2.7-.1-1 0-1.8 0-2.7.2-1.4.4-2.2 1.3-2.4 2.5-.1.6-.1 1.2-.1 1.9V62h-7.4V40.4c0-1.4 0-2.4.1-3.8.4-3.8 3-5.9 6.2-6.9 2-.6 3.4-.6 6.7-.6 3.1 0 4.3.1 5.9.5 1.3.3 2.5.7 3.4 1.5.7-.8 1.8-1.2 3.2-1.5 1.6-.4 2.8-.5 5.9-.5 3.2 0 4.7 0 6.7.6 3.2 1 5.7 3.1 6.2 6.9.1 1.3.1 2.4.1 3.8v21.7H197V39.8c0-.7 0-1.3-.1-1.9-.1-1.3-1-2.2-2.4-2.5-.9-.2-1.8-.2-2.7-.2-.9 0-1.8 0-2.7.1-2 .3-3.2 1.3-3.2 3.6v23.2h-7.3zM219.2 25.6h-7.4v-6.1h7.4v6.1zm0 36.5h-7.4V29.2h7.4v32.9zM248.6 56.1v6h-8.8c-2.9 0-6.3 0-8.3-.6-4.6-1.5-5.8-4.6-5.8-9.7v-14c0-4.6 2.8-7.6 7.1-8.3 1.5-.2 4.1-.3 7.1-.3h8.4v6H240c-1.6 0-2.6 0-3.8.1-2 .2-3.2 1.5-3.2 3.3v12.9c0 1.9.3 3.8 2.4 4.4 1 .2 2.9.2 4.3.2h8.9z"></path>
		</g>
	</svg>

	<br />

	<div style="margin-top: 10px; margin-right: 20pt; float: right;">
		Burgemeester Wuiteweg 39b - 9203 KA Drachten<br />
		T. 085 40 11 580 - F. 085 40 11 589<br />
		<a href="mailto:info@pronamic.nl" style="color: #F9461C; text-decoration: none;">info@pronamic.nl</a> - <a href="https://www.pronamic.nl/" style="color: #F9461C; text-decoration: none;">www.pronamic.nl</a><br />
		KVK: 01108446<br />
		BTW: NL8143.27.394.B01<br />
		IBAN: NL56 RABO 0108 6347 79<br />
		BIC: RABONL2U
	</div>
</div>

<div style="clear: both;"></div>

<br />

<table>
	<tr>
		<th align="left" scope="row">
			<strong>Factuurdatum:</strong>
		</th>
		<td>
			<?php echo esc_html( $header->get_date()->format( 'd/m/Y' ) ); ?>
		</td>
	</tr>
	<tr>
		<th align="left" scope="row">
			<strong>Vervaldatum:</strong>
		</th>
		<td>
			<?php echo esc_html( $header->get_due_date()->format( 'd/m/Y' ) ); ?>
		</td>
	</tr>
	<tr>
		<th align="left" scope="row">
			<strong>Factuurnummer:</strong>
		</th>
		<td>
			<?php echo esc_html( $header->get_number() ); ?>
		</td>
	</tr>
	<tr>
		<th align="left" scope="row">
			<strong>Klantnummer:</strong>
		</th>
		<td>
			<?php echo esc_html( $header->get_customer() ); ?>
		</td>
	</tr>
	<tr>
		<th align="left" scope="row">
			<strong>Btw-nummer:</strong>
		</th>
		<td>

		</td>
	</tr>
</table>

<br />

<div>
	<?php

	echo wp_kses(
		nl2br( $header->get_header_text() ),
		array(
			'br' => array(),
		)
	);

	?>
</div>

<br />

<table class="pronamic-invoice-lines-table" width="100%">
	<thead>
		<tr>
			<th align="left" scope="col" style="border-bottom: 1px solid #D3D3D3;">
				Aantal
			</th>
			<th align="left" scope="col" style="border-bottom: 1px solid #D3D3D3;">
				Omschrijving
			</th>
			<th align="right" scope="col" style="border-bottom: 1px solid #D3D3D3;">
				Bedrag
			</th>
		</tr>
	</thead>

	<tfoot>
		<tr>
			<th align="right" valign="top" colspan="2" scope="row">
				<strong>Totaal (excl. btw)</strong>
			</th>
			<td align="right" valign="top">
				<?php echo esc_html( twinfield_price( $twinfield_sales_invoice->get_totals()->get_value_excl() ) ); ?>
			</td>
		</tr>

		<?php foreach ( $twinfield_sales_invoice->get_vat_lines() as $vat_line ) : ?>

			<tr>
				<th align="right" valign="top" colspan="2" scope="row">
					<?php

					$label = 'Btw';

					$vat_code = $vat_line->get_vat_code();

					if ( null !== $vat_code ) {
						$label = $vat_code->get_name();
						$label = str_replace( 'BTW', 'btw', $label );
						$label = ucfirst( $label );
					}

					echo esc_html( $label );

					?>
				</th>
				<td align="right" valign="top">
					<?php echo esc_html( twinfield_price( $vat_line->get_vat_value() ) ); ?>
				</td>
			</tr>

		<?php endforeach; ?>

		<tr>
			<th align="right" valign="top" colspan="2" scope="row">
				<strong>Totaal (incl. btw)</strong>
			</th>
			<td align="right" valign="top">
				<?php echo esc_html( twinfield_price( $twinfield_sales_invoice->get_totals()->get_value_inc() ) ); ?>
			</td>
		</tr>
	</tfoot>

	<tbody>

		<?php foreach ( $lines as $line ) : ?>

			<tr>
				<td valign="top" style="border-bottom: 1px solid #D3D3D3; padding: 5px 0;">
					<?php echo esc_html( $line->get_quantity() ); ?>
				</td>
				<td valign="top" style="border-bottom: 1px solid #D3D3D3; padding: 5px 0;">
					<?php echo esc_html( $line->get_description() ); ?><br />

					<span style="color: #F9461C; font-size: 8pt;"><?php

					echo esc_html( $line->get_free_text_1() );
					echo ' ';
					echo esc_html( $line->get_free_text_2() );
					echo ' ';
					echo esc_html( $line->get_free_text_3() );

					?></span>
				</td>
				<td align="right" valign="top" style="border-bottom: 1px solid #D3D3D3; padding: 5px 0;">
					<?php echo esc_html( twinfield_price( $line->get_value_excl() ) ); ?></td>
				</td>
			</tr>

		<?php endforeach; ?>

	</tbody>
</table>

<br />

<div>
	<?php

	echo wp_kses(
		nl2br( $header->get_footer_text() ),
		array(
			'br' => array(),
		)
	);

	?>
</div>

<br />

<table>
	<tr>
		<th align="left" scope="row">
			<strong>Bedrag:</strong>
		</th>
		<td>
			<span style="font-family: Courier New;"><?php echo esc_html( twinfield_price( $twinfield_sales_invoice->get_totals()->get_value_inc() ) ); ?></span>
		</td>
	</tr>
	<tr>
		<th align="left" scope="row">
			<strong>Naar rekening:</strong>
		</th>
		<td>
			<span style="font-family: Courier New;">NL56 RABO 0108 6347 79</span>
		</td>
	</tr>
	<tr>
		<th align="left" scope="row">
			<strong>Ten name van:</strong>
		</th>
		<td>
			<span style="font-family: Courier New;">Pronamic</span>
		</td>
	</tr>
	<tr>
		<th align="left" scope="row">
			<strong>Betalingskenmerk:</strong>
		</th>
		<td>
			<span style="font-family: Courier New;"><?php echo esc_html( $header->get_customer() ); ?> / <?php echo esc_html( $header->get_number() ); ?></span>
		</td>
	</tr>
</table>

<p>
	Gebruik onderstaande links om de factuur online te betalen:
</p>


<table>
	<tr>
		<th align="left" scope="row">
			<strong>Nederlands</strong>
		</th>
		<td>
			<?php

			$url = 'https://www.pronamic.nl/betaal/';

			$url = add_query_arg(
				array(
					'referentie' => $header->get_number(),
					'bedrag'     => $twinfield_sales_invoice->get_value_inc(),
				),
				$url
			);

			printf(
				'<a href="%s">%s</a>',
				esc_url( $url ),
				esc_html( $url )
			);

			?>
		</td>
	</tr>
	<tr>
		<th align="left" scope="row">
			<strong>English</strong>
		</th>
		<td>
			<?php

			$url = 'https://www.pronamic.eu/pay/';

			$url = add_query_arg(
				array(
					'reference' => $header->get_number(),
					'amount'    => $twinfield_sales_invoice->get_value_inc(),
				),
				$url
			);

			printf(
				'<a href="%s">%s</a>',
				esc_url( $url ),
				esc_html( $url )
			);

			?>
		</td>
	</tr>
</table>
