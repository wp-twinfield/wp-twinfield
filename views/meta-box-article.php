<?php wp_nonce_field( 'twinfield_article', 'twinfield_article_nonce' ); ?>

<table class="form-table">
	<tr>
		<th>
			<label for="twinfield_article_code"><?php _e( 'Article Code', 'twinfield' ); ?></label>
		</th>
		<td>
			<input type="text" id="twinfield_article_code" name="twinfield_article_code" value="<?php echo $twinfield_article_code; ?>" />
		</td>
	</tr>
	<tr>
		<th>
			<label for="twinfield_subarticle_code"><?php _e( 'Subarticle Code', 'twinfield' ); ?></label>
		</th>
		<td>
			<input type="text" id="twinfield_subarticle_code" name="twinfield_subarticle_code" value="<?php echo $twinfield_subarticle_code; ?>" />
		</td>
	</tr>
</table>
