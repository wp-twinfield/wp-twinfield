<?php

$tax_classes_vat_codes = get_option( 'twinfield_woocommerce_tax_classes_vat_codes' );
$tax_classes_vat_codes = is_array( $tax_classes_vat_codes ) ? $tax_classes_vat_codes : array();

?>
<table class="widefat">
	<thead>
		<tr>
			<th width="25%"><?php esc_html_e( 'Rates', 'twinfield_woocommerce' ); ?></th>
			<th><?php esc_html_e( 'VAT Code', 'twinfield_woocommerce' ); ?></th>
		</tr>
	</thead>

	<tbody>
		<?php foreach ( $sections as $key => $label ) : ?>

			<tr>
				<?php
				$vat_code = '';
				if ( isset( $tax_classes_vat_codes[ $key ] ) ) {
					$vat_code = $tax_classes_vat_codes[ $key ];
				}
				?>
				<td width="25%">
					<?php echo esc_html( $label ); ?>
				</td>
				<td>
					<input type="text" value="<?php echo esc_attr( $vat_code ); ?>" name="twinfield_woocommerce_tax_classes_vat_codes[<?php echo esc_attr( $key ); ?>]" />
				</td>
			</tr>

		<?php endforeach; ?>

	</tbody>
</table>
