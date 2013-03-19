<script type="text/javascript">
	;(function($) {

		$(function(){

			$('.jAddLine').click(function(e) {
				e.preventDefault();

				linesRow = jQuery('.jLinesRow');

				var lastLineEntry = linesRow.children('tr').last();
				var number = lastLineEntry.data('number');
				var nextNumber = number + 1;

				lastLineEntry.clone().appendTo('.jLinesRow').data('number', nextNumber).find('input').each( function(index) {
					var self = $(this);
					var currentName = self.attr('name');
					var newName = currentName.replace(number, nextNumber, "gi");
					self.attr('name', newName);
				});
			});
		});

	})(jQuery);
</script>
<form method="POST" class="input-form">
	<?php echo $nonce; ?>
	<table class="form-table">
		<tr>
			<th><?php _e( 'Invoice Type', 'twinfield' ); ?></th>
			<td>
				<input type="text" name="invoiceType" value="<?php echo $invoice->getType(); ?>"/>
			</td>
		</tr>
		<tr>
			<th><?php _e( 'Customer ID', 'twinfield' ); ?></th>
			<td>
				<input type="text" name="customerID" value="<?php echo $invoice->getCustomer()->getID(); ?>"/>
			</td>
		</tr>
	</table>
	<hr/>
	<table class="widefat">
		<thead>
			<th><?php _e( 'Article', 'twinfield' ); ?></th>
			<th><?php _e( 'Subarticle', 'twinfield' ); ?></th>
			<th><?php _e( 'Quantity', 'twinfield' ); ?></th>
			<th><?php _e( 'Units', 'twinfield' ); ?></th>
			<th><?php _e( 'Units Excl', 'twinfield' ); ?></th>
			<th><?php _e( 'Vatcode', 'twinfield' ); ?></th>
		</thead>
		<tbody class="jLinesRow">
			<?php $lines = $invoice->getLines(); ?>
			<?php if ( ! empty( $lines ) ) : ?>
				<?php foreach ( $invoice->getLines() as $line ) : ?>
					<tr>
						<td><input type="text" name="lines[1][article]" value="<?php echo $line->getArticle(); ?>"/></td>
						<td><input type="text" name="lines[1][subarticle]" value="<?php echo $line->getSubArticle(); ?>"/></td>
						<td><input type="text" name="lines[1][quantity]" value="<?php echo $line->getQuantity(); ?>"/></td>
						<td><input type="text" name="lines[1][units]" value="<?php echo $line->getUnits(); ?>"/></td>
						<td><input type="text" name="lines[1][unitspriceexcl]" value="<?php echo $line->getUnitsPriceExcl(); ?>"/></td>
						<td><input type="text" name="lines[1][vatcode]" value="<?php echo $line->getVatCode(); ?>"/></td>
					</tr>
				<?php endforeach; ?>
			<?php else: ?>
				<tr data-number="1">
					<td><input type="text" name="lines[1][article]" value=""/></td>
					<td><input type="text" name="lines[1][subarticle]" value=""/></td>
					<td><input type="text" name="lines[1][quantity]" value=""/></td>
					<td><input type="text" name="lines[1][units]" value=""/></td>
					<td><input type="text" name="lines[1][unitspriceexcl]" value=""/></td>
					<td><input type="text" name="lines[1][vatcode]" value=""/></td>
				</tr>
			<?php endif; ?>
		</tbody>
	</table>
	<hr/>
	<a href="#" class="jAddLine">Add Line</a>
	<input type="submit" value="Send" class="button button-primary" style="float:right;"/>
</form>