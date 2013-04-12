<table class="widefat">
	<thead>
		<tr>
			<th><?php _e( 'Post Title', 'twinfield' ); ?></th>
			<th><?php _e( 'Meta Field', 'twinfield' ); ?></th>
			<th><?php _e( 'Matched?', 'twinfield' ); ?></th>
		</tr>
	</thead>
	<tbody>
		<?php while ( $posts->have_posts() ) : ?>
			<?php $posts->the_post(); ?>
			<tr>
				<td><?php the_title(); ?></td>
				<td><?php echo get_post_meta( get_the_ID(), $meta_field, true ); ?></td>
				<td>
					<?php if ( array_key_exists( get_the_ID(), $matches ) ) : ?>
					<form method="POST">
						<input type="hidden" name="action" value="merger_tool" />
						<input type="hidden" name="post_id" value="<?php echo get_the_ID(); ?>"/>
						<input type="hidden" name="new_field" value="<?php echo $new_meta_key; ?>"/>
						<input type="hidden" name="new_field_value" value="<?php echo $matches[get_the_ID()]->getID(); ?>"/>
						<?php submit_button(__( 'Sync', 'twinfield' ), 'primary', null, false ); ?>
					</form>
					<?php endif;?>
				</td>
			</tr>
		<?php endwhile; ?>
	</tbody>
</table>

