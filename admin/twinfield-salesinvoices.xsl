<?xml version="1.0"?>

<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<xsl:template match="/">
		<div class="twinfield-invoices">
			<xsl:apply-templates />
		</div>
	</xsl:template>
	
	<xsl:template match="salesinvoice">
		<div class="twinfield-invoice">
			<xsl:apply-templates select="header" />

			<xsl:apply-templates select="lines" />
		</div>
	</xsl:template>

	<xsl:template match="salesinvoice/header">			
		<xsl:if test="@msg">
			<em><xsl:value-of select="@msg" /></em>
		</xsl:if>
		
		<dl>
			<dt>Invoice Type</dt>
			<dd>
				<xsl:value-of select="invoicetype" />
				
				<xsl:if test="invoicetype/@msg">
					<em><xsl:value-of select="invoicetype/@msg" /></em>
				</xsl:if>
			</dd>
			
			<dt>Customer</dt>
			<dd>
				<xsl:value-of select="customer" />
				
				<xsl:if test="customer/@msg">
					<em><xsl:value-of select="customer/@msg" /></em>
				</xsl:if>
			</dd>
		</dl>
	</xsl:template>

	<xsl:template match="salesinvoice/lines">
		<table class="twinfield-invoice-lines">
			<thead>
				<tr>
					<th>Quantity</th>
					<th>Article</th>
					<th>Sub article</th>
					<th>Description</th>
					<th>Units price excl</th>
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
