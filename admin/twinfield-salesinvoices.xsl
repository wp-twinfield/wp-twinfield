<?xml version="1.0"?>

<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<xsl:template match="/">
		<h2>Twinfield Invoices</h2>

		<xsl:apply-templates />
	</xsl:template>
	
	<xsl:template match="salesinvoice">
		<h3>Invoice</h3>

		<xsl:apply-templates />
	</xsl:template>

	<xsl:template match="salesinvoice/header">
		<h4>Header</h4>
		
		<xsl:value-of select="@msg" />
		
		<dl>
			<dt>Invoice Type</dt>
			<dd>
				<xsl:value-of select="invoicetype" />
				
				<xsl:value-of select="invoicetype/@msg" />
			</dd>
		</dl>
	</xsl:template>

	<xsl:template match="salesinvoice/lines">
		<table>
			<thead>
				<tr>
					<th>Quantity</th>
					<th>Article</th>
					<th>Sub article</th>
					<th>Description</th>
					<th>unitspriceexcl</th>
				</tr>
			</thead>

			<tbody>
				<xsl:for-each select="line">
					<tr>
						<td>
							<xsl:value-of select="quantity" />
						</td>
						<td>
							<xsl:value-of select="article" />
						</td>
						<td>
							<xsl:value-of select="subarticle" />
						</td>
						<td>
							<xsl:value-of select="description" />
						</td>
						<td>
							<xsl:value-of select="unitspriceexcl" />
						</td>
					</tr>
				</xsl:for-each>
			</tbody>
		</table>
	</xsl:template>

</xsl:stylesheet>
