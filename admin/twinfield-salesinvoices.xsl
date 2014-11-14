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
					<th scope="col">Quantity</th>
					<th scope="col">Article</th>
					<th scope="col">Sub article</th>
					<th scope="col">Description</th>
					<th scope="col">Units price excl</th>
					<th scope="col">VAT Code</th>
				</tr>
			</thead>

			<tbody>
				<xsl:for-each select="line">
					<tr>
						<td>
							<xsl:value-of select="quantity" />
							
							<xsl:if test="quantity/@msg">
								<em><xsl:value-of select="quantity/@msg" /></em>
							</xsl:if>
						</td>
						<td>
							<xsl:value-of select="article" />
							
							<xsl:if test="article/@msg">
								<em><xsl:value-of select="article/@msg" /></em>
							</xsl:if>
						</td>
						<td>
							<xsl:value-of select="subarticle" />
							
							<xsl:if test="subarticle/@msg">
								<em><xsl:value-of select="subarticle/@msg" /></em>
							</xsl:if>
						</td>
						<td>
							<xsl:value-of select="description" />
							
							<xsl:if test="description/@msg">
								<em><xsl:value-of select="description/@msg" /></em>
							</xsl:if>
						</td>
						<td>
							<xsl:value-of select="unitspriceexcl" />

							<xsl:if test="unitspriceexcl/@msg">
								<em><xsl:value-of select="unitspriceexcl/@msg" /></em>
							</xsl:if>
						</td>
						<td>
							<xsl:value-of select="vatcode" />

							<xsl:if test="vatcode/@msg">
								<em><xsl:value-of select="vatcode/@msg" /></em>
							</xsl:if>
						</td>
					</tr>
				</xsl:for-each>
			</tbody>
		</table>
	</xsl:template>

</xsl:stylesheet>
