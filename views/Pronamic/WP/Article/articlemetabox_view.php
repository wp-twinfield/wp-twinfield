<?php echo $nonce; ?>
<table class="form-table">
	<tr>
		<th><label for="twinfield_article_id"><?php _e( 'Article ID', 'twinfield' ); ?></label></th>
		<td><input type="text" id="twinfield_article_id" name="twinfield_article_id" value="<?php echo $twinfield_article_id; ?>" /></td>
	</tr>
	<tr>
		<th><label for="twinfield_subarticle_id"><?php _e( 'Subarticle ID', 'twinfield' ); ?></label></th>
		<td><input type="text" id="twinfield_subarticle_id" name="twinfield_subarticle_id" value='<?php echo $twinfield_subarticle_id; ?>' /></td>
	</tr>
</table>