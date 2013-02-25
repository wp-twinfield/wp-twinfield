<div class="wrap">
	<?php screen_icon( 'twinfield' ); ?>

	<h2><?php echo get_admin_page_title(); ?></h2>

	<?php 
	
	$sections = array(
		'templates-nl' => array(
			'name'      => 'Templates (Dutch)',
			'url'       => 'http://twinfield.nl/', 
			'resources' => array(
				array(
					'url'     => 'http://remcotolsma.nl/wp-content/uploads/uitleg_Word_sjabloon.doc',
					'name'    => 'uitleg_Word_sjabloon.doc',
					'date'    => new DateTime( '06-08-2012 13:18' )
				),
				array(
					'url'     => 'http://remcotolsma.nl/wp-content/uploads/sjabloon-NL-FactuurExclBTW-4.1.docx',
					'name'    => 'sjabloon-NL-FactuurExclBTW-4.1.docx',
					'date'    => new DateTime( '16-07-2012 13:48' )
				),
				array(
					'url'     => 'http://remcotolsma.nl/wp-content/uploads/sjabloon-NL-TimePlus-1.2.dot',
					'name'    => 'sjabloon-NL-TimePlus-1.2.dot',
					'date'    => new DateTime( '30-06-2011 13:12' )
				),
				array(
					'url'     => 'http://remcotolsma.nl/wp-content/uploads/sjabloon-NL-Time-1.3.dot',
					'name'    => 'sjabloon-NL-Time-1.3.dot',
					'date'    => new DateTime( '30-06-2011 13:12' )
				),
				array(
					'url'     => 'http://remcotolsma.nl/wp-content/uploads/sjabloon-NL-FactuurInclBTW-4.1.dot',
					'name'    => 'sjabloon-NL-FactuurInclBTW-4.1.dot',
					'date'    => new DateTime( '30-06-2011 13:12' )
				),
				array(
					'url'     => 'http://remcotolsma.nl/wp-content/uploads/sjabloon-NL-BetalenIncasseren-1.2.dot',
					'name'    => 'sjabloon-NL-BetalenIncasseren-1.2.dot',
					'date'    => new DateTime( '30-06-2011 13:11' )
				),
				array(
					'url'     => 'http://remcotolsma.nl/wp-content/uploads/sjabloon-NL-Aanmaning-1.6.dot',
					'name'    => 'sjabloon-NL-Aanmaning-1.6.dot',
					'date'    => new DateTime( '30-06-2011 13:10' )
				)
			)
		),
		'templates-en' => array(
			'name'      => 'Templates (English)',
			'url'       => 'http://twinfield.nl/', 
			'resources' => array(
				array(
					'url'     => 'http://remcotolsma.nl/wp-content/uploads/ChequeTemplate.doc',
					'name'    => 'ChequeTemplate.doc',
					'date'    => new DateTime( '27-09-2012 12:21' )
				),
				array(
					'url'     => 'http://remcotolsma.nl/wp-content/uploads/explanation_Word_template.doc',
					'name'    => 'explanation_Word_template.doc',
					'date'    => new DateTime( '06-08-2012 13:20' )
				),
				array(
					'url'     => 'http://remcotolsma.nl/wp-content/uploads/template-EN-TimePlus-1.3.dot',
					'name'    => 'template-EN-TimePlus-1.3.dot',
					'date'    => new DateTime( '30-06-2011 16:42' )
				),
				array(
					'url'     => 'http://remcotolsma.nl/wp-content/uploads/template-EN-Time-1.4.dot',
					'name'    => 'template-EN-Time-1.4.dot',
					'date'    => new DateTime( '30-06-2011 16:41' )
				),
				array(
					'url'     => 'http://remcotolsma.nl/wp-content/uploads/template-EN-PayCollect-1.2.dot',
					'name'    => 'template-EN-PayCollect-1.2.dot',
					'date'    => new DateTime( '30-06-2011 16:41' )
				),
				array(
					'url'     => 'http://remcotolsma.nl/wp-content/uploads/template-EN-Invoice-VatIncl-4.1.dot',
					'name'    => 'template-EN-Invoice-VatIncl-4.1.dot',
					'date'    => new DateTime( '30-06-2011 16:40' )
				),
				array(
					'url'     => 'http://remcotolsma.nl/wp-content/uploads/template-EN-Invoice-VatExcl-4.1.dot',
					'name'    => 'template-EN-Invoice-VatExcl-4.1.dot',
					'date'    => new DateTime( '30-06-2011 16:39' )
				),
				array(
					'url'     => 'http://remcotolsma.nl/wp-content/uploads/template-EN-Dunning-1.6.dot',
					'name'    => 'template-EN-Dunning-1.6.dot',
					'date'    => new DateTime( '30-06-2011 16:39' )
				)
			)
		)
	);

	foreach ( $sections as $section ): ?>
	
		<h3>
			<?php echo $section['name']; ?>
			<small><a href="<?php echo $section['url']; ?>"><?php echo $section['url']; ?></a></small>
		</h3>
	
		<ul>
	
			<?php foreach ( $section['resources'] as $resource ): ?>
	
				<li>
					<?php 
					
					$href = null;
		
					if ( isset( $resource['path'] ) ) {
						$href = plugins_url( $resource['path'], Twinfield::$file );
					}
		
					if ( isset( $resource['url'] ) ) {
						$href = $resource['url'];
					}
					
					?>
					<a href="<?php echo $href; ?>">
						<?php echo $resource['name']; ?>
		
						<?php if ( isset( $resource['version'] ) ): ?>
							<small><?php printf( __( 'version %s', 'pronamic_ideal' ), $resource['version'] ); ?> </small>
						<?php endif; ?>
		
						<?php if ( isset( $resource['date'] ) ): ?>
							<small><?php printf( __( '%s', 'pronamic_ideal' ), $resource['date']->format( 'd-m-Y' ) ); ?> </small>
						<?php endif; ?>
					</a>
				</li>
	
			<?php endforeach; ?>
	
		</ul>
	
	<?php endforeach; ?>
</div>