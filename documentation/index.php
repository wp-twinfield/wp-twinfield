<!doctype html>

<html lang="en">
	<head>
		<!-- Required meta tags -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

		<!-- Bootstrap CSS -->
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

		<title>Twinfield Documentation</title>
	</head>

	<body>
		<h1>Twinfield Documentation</h1>

		<h2>
			<a name="ie-diagram"></a>
			Entity Relationship Diagram
		</h2>

		<p>
			<img src="diagram.png" class="img-fluid" alt="" />
		</p>

		<h2>
			<a name="query-examples"></a>
			Query Examples
		</h2>

		<h3>
			<a name="query-transaction-lines"></a>
			Query Transaction Lines
		</h3>
<pre>
SELECT
	*
FROM
	wp_lookup_transaction_lines AS transaction_line
		LEFT JOIN
	wp_lookup_transactions AS transaction
			ON transaction_line.transaction_id = transaction.id
		LEFT JOIN
	wp_lookup_journals AS journal
			ON transaction.journal_id = journal.id
		LEFT JOIN
	wp_lookup_offices AS office
			ON journal.office_id = office.id
		LEFT JOIN
	wp_lookup_vat_codes AS vat_code
			ON transaction_line.vat_code_id = vat_code.id
		LEFT JOIN
	wp_lookup_dimensions AS dimension_1
			ON transaction_line.dimension_1_id = dimension_1.id
		LEFT JOIN
	wp_lookup_dimensions AS dimension_2
			ON transaction_line.dimension_2_id = dimension_2.id
		LEFT JOIN
	wp_lookup_dimensions AS dimension_3
			ON transaction_line.dimension_3_id = dimension_3.id
		LEFT JOIN
	wp_lookup_users AS user
			ON transaction.user_id = user.id
LIMIT
	0, 10
;
</pre>

		<h2>
			<a name="organisations"></a>
			Organisations
		</h2>

		<p>
			<a href="https://writingexplained.org/organisation-vs-organization-difference">https://writingexplained.org/organisation-vs-organization-difference</a>
		</p>

		<?php

		$fields = array(
			'code'           => array(
				'label_nl' => 'Code',
				'label_en' => 'Code',
				'synchronized' => false,
				'mysql_column' => 'organisations.code',
			),
			'name'           => array(
				'label_nl' => 'Naam',
				'label_en' => 'Name',
				'synchronized' => false,
				'mysql_column' => 'organisations.name',
			),
			'address_line_1' => array(
				'label_nl' => 'Adresregel 1',
				'label_en' => 'Address line 1',
				'synchronized' => false,
				'mysql_column' => null,
			),
			'address_line_2' => array(
				'label_nl' => 'Adresregel 2',
				'label_en' => 'Address line 2',
				'synchronized' => false,
				'mysql_column' => null,
			),
			'address_line_3' => array(
				'label_nl' => 'Adresregel 3',
				'label_en' => 'Address line 3',
				'synchronized' => false,
				'mysql_column' => null,
			),
			'address_line_4' => array(
				'label_nl' => 'Adresregel 4',
				'label_en' => 'Address line 4',
				'synchronized' => false,
				'mysql_column' => null,
			),
			'address_line_5' => array(
				'label_nl' => 'Adresregel 5',
				'label_en' => 'Address line 5',
				'synchronized' => false,
				'mysql_column' => null,
			),
			'address_line_6' => array(
				'label_nl' => 'Adresregel 6',
				'label_en' => 'Address line 6',
				'synchronized' => false,
				'mysql_column' => null,
			),
			'city'           => array(
				'label_nl' => 'Plaats',
				'label_en' => 'City',
				'synchronized' => false,
				'mysql_column' => null,
			),
			'country_code'   => array(
				'label_nl' => 'Land',
				'label_en' => 'Country',
				'synchronized' => false,
				'mysql_column' => null,
			),
			'country_name'   => array(
				'label_nl' => 'Land',
				'label_en' => 'Country',
				'synchronized' => false,
				'mysql_column' => null,
			),
			'postal_code'    => array(
				'label_nl' => 'Postcode',
				'label_en' => 'Postcode',
				'synchronized' => false,
				'mysql_column' => null,
			),
			'phone_number'   => array(
				'label_nl' => 'Telefoon',
				'label_en' => 'Telephone',
				'synchronized' => false,
				'mysql_column' => null,
			),
			'fax_number'     => array(
				'label_nl' => 'Fax',
				'label_en' => 'Fax',
				'synchronized' => false,
				'mysql_column' => null,
			),
			'password_expiration_period' => array(
				'label_nl' => 'Wachtwoord geldigheidsperiode',
				'label_en' => 'Password expiration period',
				'synchronized' => false,
				'mysql_column' => null,
			),
			'logo' => array(
				'label_nl' => 'Logo',
				'label_en' => 'Logo',
				'synchronized' => false,
				'type'     => 'file',
				'mysql_column' => null,
			),	
		);

		?>
		<table class="table table-striped">
			<thead>
				<tr>
					<th scope="column">Key</th>
					<th scope="column">English</th>
					<th scope="column">Dutch</th>
					<th scope="column">Synchronized</th>
					<th scope="column">MySQL Column</th>
				</tr>
			</thead>

			<tbody>
				
				<?php foreach ( $fields as $key => $field ) : ?>

					<tr>
						<td>
							<?php echo $key; ?>
						</td>
						<td>
							<?php echo $field['label_en']; ?>
						</td>
						<td>
							<?php echo $field['label_nl']; ?>
						</td>
						<td>
							<?php echo $field['synchronized'] ? '✓' : '✗'; ?>
						</td>
						<td>
							<?php echo $field['mysql_column']; ?>
						</td>
					</tr>

				<?php endforeach; ?>

			</tbody>

		</table>

		<h2>
			<a name="users"></a>
			Users
		</h2>

		<?php

		$fields = array(
			'code'           => array(
				'label_nl'     => 'Code',
				'label_en'     => 'Code',
				'synchronized' => true,
				'mysql_column' => 'users.code',
			),
			'name'           => array(
				'label_nl'     => 'Naam',
				'label_en'     => 'Name',
				'synchronized' => true,
				'mysql_column' => 'users.name',
			),
			'short_name' => array(
				'label_nl'     => 'Verkorte naam',
				'label_en'     => 'Short name',
				'synchronized' => true,
				'mysql_column' => 'users.shortname',
			),
			'password' => array(
				'label_nl'     => 'Wachtwoord',
				'label_en'     => 'Password',
				'synchronized' => false,
				'mysql_column' => null,
			),
			'email' => array(
				'label_nl'     => 'E-mailadres',
				'label_en'     => 'E-mail address',
				'type'         => 'email',
				'synchronized' => false,
				'mysql_column' => null,
			),
			'culture' => array(
				'label_nl'     => 'Taal',
				'label_en'     => 'Language',
				'synchronized' => false,
				'type'         => 'select',
				'options'      => array(
					'en-GB',
					'nl-NL',
					'nl-BE',
					'de-DE',
					'fr-FR',
					'fr-BE',
					'da-DK',
					'cs-CZ',
					'hu-HU',
				),
				'mysql_column' => null,
			),
			'role' => array(
				'label_nl'     => 'Rol',
				'label_en'     => 'Role',
				'synchronized' => false,
				'type'         => 'select',
				'options'      => array(
					'601'       => '601',
					'BOX'       => 'BOX',
					'DV'        => 'Dutch VAT',
					'SALES'     => 'Factureren',
					'PA'        => 'GO',
					'LVL1'      => 'Level 1',
					'LVL2'      => 'Level 2',
					'LVL3'      => 'Level 3',
					'LVL4'      => 'Level 4',
					'LVL5'      => 'Level 5',
					'LVL7'      => 'Level 7',
					'LVL8'      => 'Level 8',
					'LVL9'      => 'Level 9',
					'LVL6'      => 'Meekijken',
					'600'       => 'Meekijken + Facturatie',
					'SD'        => 'Service Desk',
					'STAGIAIRE' => 'Stagiaire',
				),
				'mysql_column' => null,
			),
			'type' => array(
				'label_nl'     => 'Gebruikerstype',
				'label_en'     => 'User type',
				'synchronized' => false,
				'type'         => 'select',
				'options'      => array(
					'accountant'         => 'Accountant',
					'accountantcustomer' => 'Client of an accountant',
				),
				'mysql_column' => null,
			),
			'companies' => array(
				'label_nl'     => 'Administraties',
				'label_en'     => 'Companies',
				'synchronized' => false,
				'type'         => 'select',
				'mysql_column' => null,
			),
			'default_office' => array(
				'label_nl'     => 'Standaardadministratie',
				'label_en'     => 'Default company',
				'synchronized' => false,
				'mysql_column' => null,
			),
		);

		?>
		<table class="table table-striped">
			<thead>
				<tr>
					<th scope="column">Key</th>
					<th scope="column">English</th>
					<th scope="column">Dutch</th>
					<th scope="column">Synchronized</th>
					<th scope="column">MySQL Column</th>
				</tr>
			</thead>

			<tbody>
				
				<?php foreach ( $fields as $key => $field ) : ?>

					<tr>
						<td>
							<?php echo $key; ?>
						</td>
						<td>
							<?php echo $field['label_en']; ?>
						</td>
						<td>
							<?php echo $field['label_nl']; ?>
						</td>
						<td>
							<?php echo $field['synchronized'] ? '✓' : '✗'; ?>
						</td>
						<td>
							<?php echo $field['mysql_column']; ?>
						</td>
					</tr>

				<?php endforeach; ?>

			</tbody>

		</table>

		<h2>
			<a name="offices"></a><a name="companies"></a>
			Offices
		</h2>

		<p>
			Also known as <code>Companies</code>.
		</p>

		<h3>Office Properties</h3>

		<?php

		$fields = array(
			'code'           => array(
				'label_nl'     => 'Code',
				'label_en'     => 'Code',
				'synchronized' => true,
				'mysql_column' => 'offices.code',
			),
			'name'           => array(
				'label_nl'     => 'Naam',
				'label_en'     => 'Name',
				'synchronized' => true,
				'mysql_column' => 'offices.name',
			),
			'short_name' => array(
				'label_nl'     => 'Verkorte naam',
				'label_en'     => 'Short name',
				'synchronized' => true,
				'mysql_column' => 'offices.shortname',
			),
			'chamber_of_commerce_mumber' => array(
				'label_nl'     => '',
				'label_en'     => 'Chamber of commerce',
				'synchronized' => false,
				'mysql_column' => null,
			),
		);

		?>
		<table class="table table-striped">
			<thead>
				<tr>
					<th scope="column">Key</th>
					<th scope="column">English</th>
					<th scope="column">Dutch</th>
					<th scope="column">Synchronized</th>
					<th scope="column">MySQL Column</th>
				</tr>
			</thead>

			<tbody>
				
				<?php foreach ( $fields as $key => $field ) : ?>

					<tr>
						<td>
							<?php echo $key; ?>
						</td>
						<td>
							<?php echo $field['label_en']; ?>
						</td>
						<td>
							<?php echo $field['label_nl']; ?>
						</td>
						<td>
							<?php echo $field['synchronized'] ? '✓' : '✗'; ?>
						</td>
						<td>
							<?php echo $field['mysql_column']; ?>
						</td>
					</tr>

				<?php endforeach; ?>

			</tbody>

		</table>

		<h2>
			<a name="journals"></a><a name="transaction-types"></a>
			Journals
		</h2>

		<p>
			Also known as <code>Transaction Types</code>.
		</p>

		<h3>Journal Properties</h3>

		<?php

		$fields = array(
			'code'           => array(
				'label_nl'     => 'Code',
				'label_en'     => 'Code',
				'synchronized' => true,
				'mysql_column' => 'journals.code',
			),
			'name'           => array(
				'label_nl'     => 'Naam',
				'label_en'     => 'Name',
				'synchronized' => true,
				'mysql_column' => 'journals.name',
			),
			'short_name' => array(
				'label_nl'     => 'Verkorte naam',
				'label_en'     => 'Short name',
				'synchronized' => true,
				'mysql_column' => 'journals.shortname',
			),
		);

		?>
		<table class="table table-striped">
			<thead>
				<tr>
					<th scope="column">Key</th>
					<th scope="column">English</th>
					<th scope="column">Dutch</th>
					<th scope="column">Synchronized</th>
					<th scope="column">MySQL Column</th>
				</tr>
			</thead>

			<tbody>
				
				<?php foreach ( $fields as $key => $field ) : ?>

					<tr>
						<td>
							<?php echo $key; ?>
						</td>
						<td>
							<?php echo $field['label_en']; ?>
						</td>
						<td>
							<?php echo $field['label_nl']; ?>
						</td>
						<td>
							<?php echo $field['synchronized'] ? '✓' : '✗'; ?>
						</td>
						<td>
							<?php echo $field['mysql_column']; ?>
						</td>
					</tr>

				<?php endforeach; ?>

			</tbody>

		</table>

		<h2>
			<a name="dimensions"></a>
			Dimensions
		</h2>

		<blockquote class="blockquote">
			<p class="mb-0">
				As in most accounting packages, in Twinfield it's possible to register financial transactions on ledgers and subanalyse them to for example, accounts receivable, accounts payable, cost centers, projects or assets. In Twinfield we call these Dimensions. The level of the dimension determines what data you can capture. At level 1, the balance sheet and profit and loss accounts are captured, at level 2 relations (accounts payable and accounts receivable) and cost centres and at level 3 you can register projects and assets.
			</p>

			<footer class="blockquote-footer">
				<cite title="Source Title">Twinfield</cite>
			</footer>
		</blockquote>

		<p>
			<img src="dimension-overview.png" alt="" />
		</p>

		<h3>Dimension Types</h3>

		<?php

		$dimension_types = array(
			'BAS' => 'Balancesheet',
			'PNL' => 'Profit and Loss',
			'CRD' => 'Accounts Payable',
			'DEB' => 'Accounts Receivable',
			'KPL' => 'Cost centers',
			'AST' => 'Assets',
			'PRJ' => 'Projects',
			'ACT' => 'Activities',
		);

		$data = array(
			array(
				'title'       => 'General Ledger Accounts',
				'description' => 'Dimension types BAS and PNL',
				'url'         => 'https://accounting.twinfield.com/webservices/documentation/#/ApiReference/Masters/BalanceSheets',
			),
			array(
				'title'       => 'Suppliers',
				'description' => 'Dimension type CRD',
				'url'         => 'https://accounting.twinfield.com/webservices/documentation/#/ApiReference/Masters/Suppliers',
			),
			array(
				'title'       => 'Customers',
				'description' => 'Dimension type DEB',
				'url'         => 'https://accounting.twinfield.com/webservices/documentation/#/ApiReference/Masters/Customers',
			),
			array(
				'title'       => 'Cost Centers',
				'description' => 'Dimension type KPL',
				'url'         => 'https://accounting.twinfield.com/webservices/documentation/#/ApiReference/Masters/CostCenters',
			),
			array(
				'title'       => 'Fixed Assets',
				'description' => 'Dimension type AST',
				'url'         => 'https://accounting.twinfield.com/webservices/documentation/#/ApiReference/Masters/FixedAssets',
			),
			array(
				'title'       => 'Projects',
				'description' => 'Dimension type PRJ',
				'url'         => 'https://accounting.twinfield.com/webservices/documentation/#/ApiReference/Masters/Projects',
			),
			array(
				'title'       => 'Activities',
				'description' => 'Dimension type ACT',
				'url'         => 'https://accounting.twinfield.com/webservices/documentation/#/ApiReference/Masters/Activities',
			),
		);

		?>

		<table class="table table-striped">
			<thead>
				<tr>
					<th scope="column">Title</th>
					<th scope="column">Description</th>
					<th scope="column">Link</th>
				</tr>
			</thead>

			<tbody>
				
				<?php foreach ( $data as $item ) : ?>

					<tr>
						<td>
							<?php echo $item['title']; ?>
						</td>
						<td>
							<?php echo $item['description']; ?>
						</td>
						<td>
							<?php

							printf(
								'<a href="%s">%s</a>',
								$item['url'],
								$item['url']
							);

							?>
						</td>
					</tr>

				<?php endforeach; ?>

			</tbody>
		</table>

		<h3>Dimension Properties</h3>

		<?php

		$fields = array(
			'status'           => array(
				'twinfield' => array(
					'property_name' => '@status',
					'value'         => array(
						'active',
						'deleted',
						'hide',
					),
					'description'   => 'For creating and updating status may be left empty. For deleting deleted should be used. In case a dimension that is used in a transaction is deleted, its status has been changed into hide. Hidden dimensions can be activated by using active.',
 				),
				'label_nl' => 'Status',
				'label_en' => 'Status',
				'synchronized' => false,
				'types'    => array(
					'BAS',
					'PNL',
				),
			),
			'office'           => array(
				'twinfield' => array(
					'property_name' => 'office',
					'value'         => 'code',
					'description'   => 'Office code.',
 				),
				'label_nl' => '',
				'label_en' => 'Office',
				'synchronized' => true,
				'types'    => array(
					'BAS',
					'PNL',
				),
			),
			'type'           => array(
				'twinfield' => array(
					'property_name' => 'type',
					'value'         => 'code',
					'description'   => 'Dimension type. See Dimension type.
Dimension type of balances accounts is BAS.',
 				),
				'label_nl' => '',
				'label_en' => 'Type',
				'synchronized' => true,
				'types'    => array(
					'BAS',
					'PNL',
				),
			),
			'code'           => array(
				'label_nl' => 'Code',
				'label_en' => 'Code',
				'synchronized' => true,
				'types'    => array_keys( $dimension_types ),
			),
			'uid'           => array(
				'label_nl' => '',
				'label_en' => 'uid',
				'synchronized' => false,
				'types'    => array(
					'BAS',
					'PNL',
				),
			),
			'name'           => array(
				'label_nl' => 'Naam',
				'label_en' => 'Name',
				'synchronized' => true,
				'types'    => array_keys( $dimension_types ),
			),
			'shortname' => array(
				'label_nl' => 'Verkorte naam',
				'label_en' => 'Short name',
				'synchronized' => true,
				'types'    => array_keys( $dimension_types ),
			),
			'in_use' => array(
				'label_nl'     => '',
				'label_en'     => '',
				'synchronized' => false,
				'types'        => array(
					'BAS',
					'PNL',
				),
			),
			'behaviour' => array(
				'label_nl'     => '',
				'label_en'     => '',
				'synchronized' => false,
				'types'        => array(
					'BAS',
					'PNL',
				),
			),
			'touched' => array(
				'label_nl'     => '',
				'label_en'     => '',
				'synchronized' => false,
				'types'        => array(
					'BAS',
					'PNL',
				),
			),
			'begin_period' => array(
				'label_nl'     => '',
				'label_en'     => '',
				'synchronized' => false,
				'types'        => array(
					'BAS',
					'PNL',
				),
			),
			'begin_year' => array(
				'label_nl'     => '',
				'label_en'     => '',
				'synchronized' => false,
				'types'        => array(
					'BAS',
					'PNL',
				),
			),
			'end_period' => array(
				'label_nl'     => '',
				'label_en'     => '',
				'synchronized' => false,
				'types'        => array(
					'BAS',
					'PNL',
				),
			),
			'end_year' => array(
				'label_nl'     => '',
				'label_en'     => '',
				'synchronized' => false,
				'types'        => array(
					'BAS',
					'PNL',
				),
			),
			'website' => array(
				'label_nl'     => '',
				'label_en'     => '',
				'synchronized' => false,
				'types'        => array(
					'BAS',
					'PNL',
				),
			),
			'coc_number' => array(
				'label_nl'     => '',
				'label_en'     => '',
				'synchronized' => false,
				'types'        => array(
					'BAS',
					'PNL',
				),
			),
			'vat_number' => array(
				'label_nl'     => '',
				'label_en'     => '',
				'synchronized' => false,
				'types'        => array(
					'BAS',
					'PNL',
				),
			),
			'edit_dimension_name' => array(
				'label_nl'     => '',
				'label_en'     => '',
				'synchronized' => false,
				'types'        => array(
					'BAS',
					'PNL',
				),
			),
		);

		?>
		<table class="table table-striped">
			<thead>
				<tr>
					<th scope="column">Key</th>
					<th scope="column">English</th>
					<th scope="column">Dutch</th>

					<?php foreach ( $dimension_types as $code => $name ) : ?>

						<th scope="column"><?php echo $code; ?></th>

					<?php endforeach; ?>

					<th scope="column">Synchronized</th>
				</tr>
			</thead>

			<tbody>
				
				<?php foreach ( $fields as $key => $field ) : ?>

					<tr>
						<td>
							<?php echo $key; ?>
						</td>
						<td>
							<?php echo $field['label_en']; ?>
						</td>
						<td>
							<?php echo $field['label_nl']; ?>
						</td>

						<?php foreach ( $dimension_types as $code => $name ) : ?>

							<td>
								<?php

								if ( in_array( $code, $field['types'], true ) ) {
									echo '✓';
								}

								?>
							</td>

						<?php endforeach; ?>

						<td>
							<?php echo $field['synchronized'] ? '✓' : '✗'; ?>
						</td>
					</tr>

				<?php endforeach; ?>

			</tbody>

		</table>

		<h2>
			<a name="transactions"></a>
			Transactions
		</h2>

		<h3>Transaction Types</h3>

		<?php

		$transaction_types = array(
			'Bank'     => 'Bank transactions',
			'Cash'     => 'Cash transactions',
			'Journal'  => 'Journal transactions',
			'Purchase' => 'Purchase transactions',
			'Sales'    => 'Sales transactions',
		);

		$data = array(
			array(
				'title'=> 'Bank transactions',
				'url'  => 'https://accounting.twinfield.com/webservices/documentation/#/ApiReference/Transactions/BankTransactions',
			),
			array(
				'title'=> 'Cash transactions',
				'url'  => 'https://accounting.twinfield.com/webservices/documentation/#/ApiReference/Transactions/CashTransactions',
			),
			array(
				'title'=> 'Journal transactions',
				'url'  => 'https://accounting.twinfield.com/webservices/documentation/#/ApiReference/Transactions/JournalTransactions',
			),
			array(
				'title'=> 'Purchase transactions',
				'url'  => 'https://accounting.twinfield.com/webservices/documentation/#/ApiReference/PurchaseTransactions',
			),
			array(
				'title'=> 'Sales transactions',
				'url'  => 'https://accounting.twinfield.com/webservices/documentation/#/ApiReference/SalesTransactions',
			),
		);

		?>

		<table class="table table-striped">
			<thead>
				<tr>
					<th scope="column">Title</th>
					<th scope="column">Link</th>
				</tr>
			</thead>

			<tbody>
				
				<?php foreach ( $data as $item ) : ?>

					<tr>
						<td>
							<?php echo $item['title']; ?>
						</td>
						<td>
							<?php

							printf(
								'<a href="%s">%s</a>',
								$item['url'],
								$item['url']
							);

							?>
						</td>
					</tr>

				<?php endforeach; ?>

			</tbody>
		</table>

		<h3>Transaction Header Properties</h3>

		<?php

		$fields = array(
			array(
				'name'         => 'office',
				'type'         => 'code',
				'description'  => 'Office code.',
				'synchronized' => true,
				'types'        => array(
					'Bank'
				),
			),
			array(
				'name'         => 'code',
				'type'         => 'code',
				'description'  => 'Transaction type code.',
				'synchronized' => true,
				'types'        => array(
					'Bank'
				),
			),
			array(
				'name'         => 'number',
				'type'         => 'integer',
				'description'  => 'Transaction number.',
				'description_2' => 'When creating a new bank transaction, don\'t include this tag as the transaction number is determined by the system. When updating a bank transaction, the related transaction number should be provided.',
				'synchronized' => true,
				'types'        => array(
					'Bank'
				),
			),
			array(
				'name'         => 'period',
				'type'         => 'string',
				'description'  => 'Period in YYYY/PP format. If this tag is not included or if it is left empty, the period is determined by the system based on the provided transaction date.',
				'synchronized' => true,
				'types'        => array(
					'Bank'
				),
			),
			array(
				'name'         => 'currency',
				'type'         => 'code',
				'description'  => 'Currency code.',
				'synchronized' => true,
				'types'        => array(
					'Bank'
				),
			),
			array(
				'name'         => 'date',
				'type'         => 'date',
				'description'  => 'Transaction date.',
				'description_2' => 'Optionally, it is possible to suppress warnings about \'date out of range for the given period\' by adding the raisewarning attribute and set its value to false. This overwrites the value of the raisewarning attribute as set on the root element.',
				'synchronized' => true,
				'types'        => array(
					'Bank'
				),
			),
			array(
				'name'         => 'statementnumber',
				'type'         => 'integer',
				'description'  => 'Number of the bank statement. Don\'t confuse this number with the transaction number.',
				'synchronized' => true,
				'types'        => array(
					'Bank'
				),
			),
			array(
				'name'         => 'origin',
				'type'         => 'code',
				'description'  => 'The bank transaction origin.',
				'description_2' => 'Read-only attribute.',
				'synchronized' => true,
				'types'        => array(
					'Bank'
				),
			),
			array(
				'name'         => 'startvalue',
				'type'         => 'money',
				'description'  => 'Opening balance. If not provided, the opening balance is set to zero.',
				'synchronized' => true,
				'types'        => array(
					'Bank'
				),
			),
			array(
				'name'         => 'closevalue',
				'type'         => 'money',
				'description'  => 'Closing balance. If not provided, the closing balance is set to zero.',
				'synchronized' => true,
				'types'        => array(
					'Bank'
				),
			),
			array(
				'name'         => 'modificationdate',
				'type'         => 'string',
				'description'  => 'The date/time on which the bank transaction was modified the last time.',
				'description_2' => 'Read-only attribute.',
				'synchronized' => true,
				'types'        => array(
					'Bank'
				),
			),
			array(
				'name'         => 'user',
				'type'         => 'string',
				'description'  => 'The user who created the bank transaction.',
				'description_2' => 'Read-only attribute.',
				'synchronized' => true,
				'types'        => array(
					'Bank'
				),
			),
			array(
				'name'         => 'inputdate',
				'type'         => 'string',
				'description'  => 'The date/time on which the transaction was created.',
				'description_2' => 'Read-only attribute.',
				'synchronized' => true,
				'types'        => array(
					'Bank'
				),
			),
			array(
				'name'         => 'freetext1',
				'type'         => 'text',
				'description'  => 'Free text field 1 as entered on the transaction type.',
				'synchronized' => true,
				'types'        => array(
					'Bank'
				),
			),
			array(
				'name'         => 'freetext2',
				'type'         => 'text',
				'description'  => 'Free text field 2 as entered on the transaction type.',
				'synchronized' => true,
				'types'        => array(
					'Bank'
				),
			),
			array(
				'name'         => 'freetext3',
				'type'         => 'text',
				'description'  => 'Free text field 3 as entered on the transaction type.',
				'synchronized' => true,
				'types'        => array(
					'Bank'
				),
			),
		);

		?>
		<table class="table table-striped">
			<thead>
				<tr>
					<th scope="column">Name</th>
					<th scope="column">Type</th>
					<th scope="column">Description</th>

					<?php foreach ( $transaction_types as $code => $name ) : ?>

						<th scope="column"><?php echo $code; ?></th>

					<?php endforeach; ?>

					<th scope="column">Synchronized</th>
				</tr>
			</thead>

			<tbody>
				
				<?php foreach ( $fields as $key => $field ) : ?>

					<tr>
						<td>
							<?php echo $field['name']; ?>
						</td>
						<td>
							<?php echo $field['type']; ?>
						</td>
						<td>
							<?php echo $field['description']; ?>
						</td>

						<?php foreach ( $transaction_types as $code => $name ) : ?>

							<td>
								<?php

								if ( in_array( $code, $field['types'], true ) ) {
									echo '✓';
								}

								?>
							</td>

						<?php endforeach; ?>

						<td>
							<?php echo $field['synchronized'] ? '✓' : '✗'; ?>
						</td>
					</tr>

				<?php endforeach; ?>

			</tbody>

		</table>

		<h2>
			<a name="transaction-lines"></a>
			Transaction Lines
		</h2>

		<h3>Transaction Line Properties</h3>

		<?php

		$fields = array(
			array(
				'name'         => '@type',
				'type'         => array(
					'total',
					'detail',
					'vat',
				),
				'description'  => 'Line type.',
				'synchronized' => true,
				'types'        => array(
					'Bank'
				),
				'mysql_column' => 'transaction_lines.type',
			),
			array(
				'name'         => '@id',
				'type'         => 'integer',
				'description'  => 'Line ID.',
				'synchronized' => true,
				'types'        => array(
					'Bank'
				),
				'mysql_column' => 'transaction_lines.line',
			),
			array(
				'name'         => 'dim1',
				'type'         => 'string(16)',
				'description'  => '',
				'synchronized' => true,
				'types'        => array(
					'Bank'
				),
				'mysql_column' => 'transaction_lines.dimension_1_id',
			),
			array(
				'name'         => 'dim2',
				'type'         => 'string(16)',
				'description'  => '',
				'synchronized' => true,
				'types'        => array(
					'Bank'
				),
				'mysql_column' => 'transaction_lines.dimension_2_id',
			),
			array(
				'name'         => 'dim3',
				'type'         => 'string(16)',
				'description'  => '',
				'synchronized' => true,
				'types'        => array(
					'Bank'
				),
				'mysql_column' => 'transaction_lines.dimension_3_id',
			),
			array(
				'name'         => 'dim4',
				'type'         => 'string(16)',
				'description'  => 'Not in use.',
				'synchronized' => false,
				'types'        => array(
					'Bank'
				),
				'mysql_column' => null,
			),
			array(
				'name'         => 'dim5',
				'type'         => 'string(16)',
				'description'  => 'Not in use.',
				'synchronized' => false,
				'types'        => array(
					'Bank'
				),
				'mysql_column' => null,
			),
			array(
				'name'         => 'dim6',
				'type'         => 'string(16)',
				'description'  => 'Not in use.',
				'synchronized' => false,
				'types'        => array(
					'Bank'
				),
				'mysql_column' => null,
			),
			array(
				'name'         => 'debitcredit',
				'type'         => array(
					'debit',
					'credit',
				),
				'description'  => '',
				'synchronized' => true,
				'types'        => array(
					'Bank'
				),
				'mysql_column' => 'transaction_lines.debit_credit',
			),
			array(
				'name'         => 'value',
				'type'         => 'money',
				'description'  => '',
				'synchronized' => false,
				'types'        => array(
					'Bank'
				),
				'mysql_column' => null,
			),
			array(
				'name'         => 'basevalue',
				'type'         => 'money',
				'description'  => '',
				'synchronized' => true,
				'types'        => array(
					'Bank'
				),
				'mysql_column' => 'transaction_lines.base_value',
			),
			array(
				'name'         => 'rate',
				'type'         => 'decimal',
				'description'  => 'The exchange rate used for the calculation of the base amount.',
				'synchronized' => false,
				'types'        => array(
					'Bank'
				),
				'mysql_column' => null,
			),
			array(
				'name'         => 'repvalue',
				'type'         => 'money',
				'description'  => 'Amount in the reporting currency.',
				'synchronized' => false,
				'types'        => array(
					'Bank'
				),
				'mysql_column' => null,
			),
			array(
				'name'         => 'reprate',
				'type'         => 'decimal',
				'description'  => 'The exchange rate used for the calculation of the base amount.',
				'synchronized' => false,
				'types'        => array(
					'Bank'
				),
				'mysql_column' => null,
			),
			array(
				'name'         => 'description',
				'type'         => 'string(40)',
				'description'  => 'Description of the transaction line.',
				'synchronized' => true,
				'types'        => array(
					'Bank'
				),
				'mysql_column' => 'transaction_lines.description',
			),
			array(
				'name'         => 'vattotal',
				'type'         => 'money',
				'description'  => 'Only if line type is total. The total VAT amount in the currency of the bank transaction.',
				'synchronized' => true,
				'types'        => array(
					'Bank'
				),
				'mysql_column' => 'transaction_lines.vat_total',
			),
			array(
				'name'         => 'vatbasetotal',
				'type'         => 'money',
				'description'  => 'Only if line type is total. The total VAT amount in base currency.',
				'synchronized' => false,
				'types'        => array(
					'Bank'
				),
				'mysql_column' => 'transaction_lines.vat_base_total',
			),
			array(
				'name'         => 'vatreptotal',
				'type'         => 'money',
				'description'  => 'Only if line type is total. The total VAT amount in reporting currency.',
				'synchronized' => false,
				'types'        => array(
					'Bank'
				),
				'mysql_column' => null,
			),
			array(
				'name'         => 'matchstatus',
				'type'         => 'enum',
				'options'      => array(
					'available',
					'matched',
					'proposed',
					'notmatchable',
				),
				'description'  => 'Payment status of the bank transaction.',
				'synchronized' => true,
				'types'        => array(
					'Bank'
				),
				'mysql_column' => 'transaction_lines.match_status',
			),
			array(
				'name'         => 'matchlevel',
				'type'         => 'integer',
				'description'  => 'Only if line type is detail. The level of the matchable dimension.',
				'synchronized' => false,
				'types'        => array(
					'Bank'
				),
				'mysql_column' => null,
			),
			array(
				'name'         => 'relation',
				'type'         => 'integer',
				'description'  => 'Only if line type is detail.',
				'synchronized' => false,
				'types'        => array(
					'Bank'
				),
				'mysql_column' => null,
			),
			array(
				'name'         => 'basevalueopen',
				'type'         => 'money',
				'description'  => 'Only if line type is detail. The amount still owed in base currency.',
				'synchronized' => true,
				'types'        => array(
					'Bank'
				),
				'mysql_column' => null,
			),
			array(
				'name'         => 'repvalueopen',
				'type'         => 'money',
				'description'  => 'Only if line type is detail. The amount still owed in reporting currency.',
				'synchronized' => false,
				'types'        => array(
					'Bank'
				),
				'mysql_column' => null,
			),
			array(
				'name'         => 'vatcode',
				'type'         => 'code',
				'description'  => 'Only if line type is detail or vat. VAT code.',
				'synchronized' => true,
				'types'        => array(
					'Bank'
				),
				'mysql_column' => 'transaction_lines.vat_code_id',
			),
			array(
				'name'         => 'vatvalue',
				'type'         => 'money',
				'description'  => 'Only if line type is detail. VAT amount in the currency of the bank transaction.',
				'synchronized' => false,
				'types'        => array(
					'Bank'
				),
				'mysql_column' => null,
			),
			array(
				'name'         => 'vatbasevalue',
				'type'         => 'money',
				'description'  => 'Only if line type is detail. VAT amount in base currency.',
				'synchronized' => false,
				'types'        => array(
					'Bank'
				),
				'mysql_column' => null,
			),
			array(
				'name'         => 'vatrepvalue',
				'type'         => 'money',
				'description'  => 'Only if line type is detail. VAT amount in reporting currency.',
				'synchronized' => false,
				'types'        => array(
					'Bank'
				),
				'mysql_column' => null,
			),
			array(
				'name'         => 'vatturnover',
				'type'         => 'money',
				'description'  => 'Only if line type is vat. Amount on which VAT was calculated in the currency of the bank transaction.',
				'synchronized' => false,
				'types'        => array(
					'Bank'
				),
				'mysql_column' => null,
			),
			array(
				'name'         => 'vatbaseturnover',
				'type'         => 'money',
				'description'  => 'Only if line type is vat. Amount on which VAT was calculated in base currency.',
				'synchronized' => false,
				'types'        => array(
					'Bank'
				),
				'mysql_column' => null,
			),
			array(
				'name'         => 'vatrepturnover',
				'type'         => 'money',
				'description'  => 'Only if line type is vat. Amount on which VAT was calculated in reporting currency.',
				'synchronized' => false,
				'types'        => array(
					'Bank'
				),
				'mysql_column' => null,
			),
			array(
				'name'         => 'performancetype',
				'type'         => 'enum',
				'options'      => array(
					'services',
					'goods',
				),
				'description'  => 'Only if line type is detail or vat. Mandatory in case of an ICT VAT code. The performance type.',
				'synchronized' => false,
				'types'        => array(
					'Bank'
				),
				'mysql_column' => null,
			),
		);

		?>
		<table class="table table-striped">
			<thead>
				<tr>
					<th scope="column">Name</th>
					<th scope="column">Type</th>
					<th scope="column">Description</th>

					<?php foreach ( $transaction_types as $code => $name ) : ?>

						<th scope="column"><?php echo $code; ?></th>

					<?php endforeach; ?>

					<th scope="column">Synchronized</th>
					<th scope="column">MySQL Column</th>
				</tr>
			</thead>

			<tbody>
				
				<?php foreach ( $fields as $key => $field ) : ?>

					<tr>
						<td>
							<?php echo $field['name']; ?>
						</td>
						<td>
							<?php 

							$type = $field['type'];

							if ( is_string( $type ) ) {
								echo $field['type'];
							}

							?>
						</td>
						<td>
							<?php echo $field['description']; ?>
						</td>

						<?php foreach ( $transaction_types as $code => $name ) : ?>

							<td>
								<?php

								if ( in_array( $code, $field['types'], true ) ) {
									echo '✓';
								}

								?>
							</td>

						<?php endforeach; ?>

						<td>
							<?php echo $field['synchronized'] ? '✓' : '✗'; ?>
						</td>
						<td>
							<?php echo $field['mysql_column']; ?>
						</td>
					</tr>

				<?php endforeach; ?>

			</tbody>

		</table>

		<!-- Optional JavaScript -->
		<!-- jQuery first, then Popper.js, then Bootstrap JS -->
		<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
	</body>
</html>
