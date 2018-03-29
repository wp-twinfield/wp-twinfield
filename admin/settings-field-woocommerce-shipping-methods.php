<table class="widefat">
	<thead>
		<tr>
			<th width="25%"><?php esc_html_e( 'Method', 'twinfield_woocommerce' ); ?></th>
			<th><?php esc_html_e( 'Article Code', 'twinfield_woocommerce' ); ?></th>
			<th><?php esc_html_e( 'Subarticle Code', 'twinfield_woocommerce' ); ?></th>
		</tr>
	</thead>

	<tbody>

		<?php foreach ( $shipping_methods as $shipping_method ) : ?>

			<tr>
				<?php
				$article_code = '';
				if ( isset( $shipping_method_article_codes[ $shipping_method->id ] ) ) {
					$article_code = $shipping_method_article_codes[ $shipping_method->id ];
				}
				$subarticle_code = '';
				if ( isset( $shipping_method_subarticle_codes[ $shipping_method->id ] ) ) {
					$subarticle_code = $shipping_method_subarticle_codes[ $shipping_method->id ];
				}
				?>
				<td width="25%">
					<?php echo esc_html( $shipping_method->method_title ); ?>
				</td>
				<td>
					<input type="text" value="<?php echo esc_attr( $article_code ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'shipping_method_article_codes' ) ); ?>[<?php echo esc_attr( $shipping_method->id ); ?>]" />
				</td>
				<td>
					<input type="text" value="<?php echo esc_attr( $subarticle_code ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'shipping_method_subarticle_codes' ) ); ?>[<?php echo esc_attr( $shipping_method->id ); ?>]" />
				</td>
			</tr>

		<?php endforeach; ?>

	</tbody>
</table>
