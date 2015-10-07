<div class="wrap">
	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>

	<?php

	$sections = array(
		'general' => array(
			'name'      => __( 'General', 'twinfield' ),
			'resources' => array(
				array(
					'url'     => 'http://remcotolsma.nl/wp-content/uploads/Handleiding_Twinfield_V1_HR_SVDWA.pdf',
					'name'    => 'Starten met Twinfield',
					'version' => '6.0',
				),
				array(
					'url'     => 'https://c1.twinfield.com/webservices/documentation/',
					'name'    => 'Twinfield API Documentation site',
				)
			)
		),
		'webservices' => array(
			'name'      => __( 'Webservices','twinfield' ),
			'resources' => array(
				array(
					'url'     => 'http://en.twinfield.com/webservices/training/',
					'name'    => 'Twinfield Webservices Demo Platform',
				),
				array(
					'url'     => 'http://remcotolsma.nl/wp-content/uploads/Twinfield-Webservices-Manual.pdf',
					'name'    => 'Twinfield Web services manual',
					'version' => '5.4',
					'date'    => new DateTime( '01-09-2010' ),
				),
				array(
					'url'     => 'http://remcotolsma.nl/wp-content/uploads/Webservices-Manual-Browse.pdf',
					'name'    => 'Webservices – Browse data Xml',
					'version' => '5.4',
					'date'    => new DateTime( '01-09-2010' ),
				),
				array(
					'url'     => 'http://remcotolsma.nl/wp-content/uploads/Webservices-Manual-Dimensions.pdf',
					'name'    => 'Webservices – Dimensions',
					'version' => '5.4',
					'date'    => new DateTime( '01-09-2010' ),
				),
				array(
					'url'     => 'http://remcotolsma.nl/wp-content/uploads/Webservices-Manual-Financial-Transactions.pdf',
					'name'    => 'Webservices – Financial Transactions',
					'version' => '5.1E',
					'date'    => new DateTime( '01-01-2009' ),
				),
				array(
					'url'     => 'http://remcotolsma.nl/wp-content/uploads/Webservices-Manual-Matching.pdf',
					'name'    => 'Webservices – Matching Xml',
					'version' => '5.4',
					'date'    => new DateTime( '01-01-2010' ),
				),
				array(
					'url'     => 'http://remcotolsma.nl/wp-content/uploads/Webservices-Manual-Pay_Collect.pdf',
					'name'    => 'Twinfield web services – Pay and collect',
					'version' => '5.4',
					'date'    => new DateTime( '01-10-2010' ),
				),
				array(
					'url'     => 'http://remcotolsma.nl/wp-content/uploads/Webservices-Manual-Sales-Invoices.pdf',
					'name'    => 'Twinfield Web services Sales invoices',
					'version' => '5.4',
					'date'    => new DateTime( '01-04-2010' ),
				),
				array(
					'url'     => 'http://remcotolsma.nl/wp-content/uploads/Webservices-Manual-Single-Sign-On.pdf',
					'name'    => 'Twinfield – Single Sign On',
					'version' => '5.4',
					'date'    => new DateTime( '01-04-2009' ),
				),
				array(
					'url'     => 'http://remcotolsma.nl/wp-content/uploads/Webservices-Manual-Users.pdf',
					'name'    => 'Twinfield web services – User Xml',
					'version' => '5.4',
					'date'    => new DateTime( '01-01-2010' ),
				),
				array(
					'url'     => 'http://remcotolsma.nl/wp-content/uploads/Webservices-Manual-Validations.pdf',
					'name'    => 'Webservices Validations',
					'version' => '5.3',
					'date'    => new DateTime( '01-04-2007' ),
				)
			)
		),
		'templates-nl' => array(
			'name'      => __( 'Templates (Dutch)', 'twinfield' ),
			'url'       => 'http://twinfield.nl/',
			'resources' => array(
				array(
					'url'     => 'http://remcotolsma.nl/wp-content/uploads/uitleg_Word_sjabloon.doc',
					'name'    => 'uitleg_Word_sjabloon.doc',
					'date'    => new DateTime( '06-08-2012 13:18' ),
				),
				array(
					'url'     => 'http://remcotolsma.nl/wp-content/uploads/sjabloon-NL-FactuurExclBTW-4.1.docx',
					'name'    => 'sjabloon-NL-FactuurExclBTW-4.1.docx',
					'date'    => new DateTime( '16-07-2012 13:48' ),
				),
				array(
					'url'     => 'http://remcotolsma.nl/wp-content/uploads/sjabloon-NL-TimePlus-1.2.dot',
					'name'    => 'sjabloon-NL-TimePlus-1.2.dot',
					'date'    => new DateTime( '30-06-2011 13:12' ),
				),
				array(
					'url'     => 'http://remcotolsma.nl/wp-content/uploads/sjabloon-NL-Time-1.3.dot',
					'name'    => 'sjabloon-NL-Time-1.3.dot',
					'date'    => new DateTime( '30-06-2011 13:12' ),
				),
				array(
					'url'     => 'http://remcotolsma.nl/wp-content/uploads/sjabloon-NL-FactuurInclBTW-4.1.dot',
					'name'    => 'sjabloon-NL-FactuurInclBTW-4.1.dot',
					'date'    => new DateTime( '30-06-2011 13:12' ),
				),
				array(
					'url'     => 'http://remcotolsma.nl/wp-content/uploads/sjabloon-NL-BetalenIncasseren-1.2.dot',
					'name'    => 'sjabloon-NL-BetalenIncasseren-1.2.dot',
					'date'    => new DateTime( '30-06-2011 13:11' ),
				),
				array(
					'url'     => 'http://remcotolsma.nl/wp-content/uploads/sjabloon-NL-Aanmaning-1.6.dot',
					'name'    => 'sjabloon-NL-Aanmaning-1.6.dot',
					'date'    => new DateTime( '30-06-2011 13:10' ),
				)
			)
		),
		'templates-en' => array(
			'name'      => __( 'Templates (English)', 'twinfield' ),
			'url'       => 'http://twinfield.nl/',
			'resources' => array(
				array(
					'url'     => 'http://remcotolsma.nl/wp-content/uploads/ChequeTemplate.doc',
					'name'    => 'ChequeTemplate.doc',
					'date'    => new DateTime( '27-09-2012 12:21' ),
				),
				array(
					'url'     => 'http://remcotolsma.nl/wp-content/uploads/explanation_Word_template.doc',
					'name'    => 'explanation_Word_template.doc',
					'date'    => new DateTime( '06-08-2012 13:20' ),
				),
				array(
					'url'     => 'http://remcotolsma.nl/wp-content/uploads/template-EN-TimePlus-1.3.dot',
					'name'    => 'template-EN-TimePlus-1.3.dot',
					'date'    => new DateTime( '30-06-2011 16:42' ),
				),
				array(
					'url'     => 'http://remcotolsma.nl/wp-content/uploads/template-EN-Time-1.4.dot',
					'name'    => 'template-EN-Time-1.4.dot',
					'date'    => new DateTime( '30-06-2011 16:41' ),
				),
				array(
					'url'     => 'http://remcotolsma.nl/wp-content/uploads/template-EN-PayCollect-1.2.dot',
					'name'    => 'template-EN-PayCollect-1.2.dot',
					'date'    => new DateTime( '30-06-2011 16:41' ),
				),
				array(
					'url'     => 'http://remcotolsma.nl/wp-content/uploads/template-EN-Invoice-VatIncl-4.1.dot',
					'name'    => 'template-EN-Invoice-VatIncl-4.1.dot',
					'date'    => new DateTime( '30-06-2011 16:40' ),
				),
				array(
					'url'     => 'http://remcotolsma.nl/wp-content/uploads/template-EN-Invoice-VatExcl-4.1.dot',
					'name'    => 'template-EN-Invoice-VatExcl-4.1.dot',
					'date'    => new DateTime( '30-06-2011 16:39' ),
				),
				array(
					'url'     => 'http://remcotolsma.nl/wp-content/uploads/template-EN-Dunning-1.6.dot',
					'name'    => 'template-EN-Dunning-1.6.dot',
					'date'    => new DateTime( '30-06-2011 16:39' ),
				)
			)
		),
		'libraries' => array(
			'name'      => 'Libraries',
			'resources' => array(
				array(
					'url'     => 'https://bitbucket.org/vanschelven/twinfield',
					'name'    => 'Twinfield API written in Python',
				),
				array(
					'url'     => 'http://code.google.com/p/twinfield/',
					'name'    => 'Twinfield API written in PHP',
				),
				array(
					'url'     => 'https://github.com/macernst/twinfield',
					'name'    => 'Twinfield API written in Ruby',
				)
			)
		)
	);

	foreach ( $sections as $section ): ?>

		<h3>
			<?php echo esc_html( $section['name'] ); ?>

			<?php if ( isset( $section['url'] ) ) : ?>

				<small><a href="<?php echo esc_attr( $section['url'] ); ?>"><?php echo esc_html( $section['url'] ); ?></a></small>

			<?php endif; ?>
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
					<a href="<?php echo esc_attr( $href ); ?>" target="_blank">
						<?php echo esc_html( $resource['name'] ); ?>

						<?php if ( isset( $resource['version'] ) ): ?>
							<small><?php printf( __( 'version %s', 'pronamic_ideal' ), esc_html( $resource['version'] ) ); ?> </small>
						<?php endif; ?>

						<?php if ( isset( $resource['date'] ) ): ?>
							<small><?php printf( __( '%s', 'pronamic_ideal' ), esc_html( $resource['date']->format( 'd-m-Y' ) ) ); ?> </small>
						<?php endif; ?>
					</a>
				</li>

			<?php endforeach; ?>

		</ul>

	<?php endforeach; ?>
</div>
