<?xml version="1.0"?>

<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<xsl:template match="/">
		<div class="twinfield-invoices">
			<xsl:apply-templates />
		</div>
	</xsl:template>
	
	<xsl:template match="dimension">
		<div class="twinfield-dimension">
			<dl>
				<dt>Office</dt>
				<dd>
					<xsl:value-of select="office" />
					
					<xsl:if test="office/@msg">
						<em><xsl:value-of select="office/@msg" /></em>
					</xsl:if>
				</dd>
				
				<dt>Type</dt>
				<dd>
					<xsl:value-of select="type" />
					
					<xsl:if test="type/@msg">
						<em><xsl:value-of select="type/@msg" /></em>
					</xsl:if>
				</dd>
				
				<dt>Name</dt>
				<dd>
					<xsl:value-of select="name" />
					
					<xsl:if test="name/@msg">
						<em><xsl:value-of select="name/@msg" /></em>
					</xsl:if>
				</dd>
			</dl>

			<xsl:apply-templates select="addresses" />
		</div>
	</xsl:template>

	<xsl:template match="addresses">
		<xsl:if test="@msg">
			<em><xsl:value-of select="@msg" /></em>
		</xsl:if>

		<table class="twinfield-table">
			<thead>
				<tr>
					<th scope="col">ID</th>
					<th scope="col">Type</th>
					<th scope="col">Default</th>
					<th scope="col">Name</th>
					<th scope="col">Country</th>
					<th scope="col">City</th>
					<th scope="col">Postcode</th>
					<th scope="col">Telephone</th>
					<th scope="col">Email</th>
				</tr>
			</thead>

			<tbody>
				<xsl:for-each select="address">
					<tr>
						<td>
							<xsl:value-of select="@id" />
						</td>
						<td>
							<xsl:value-of select="@type" />
						</td>
						<td>
							<xsl:value-of select="@default" />
						</td>
						<td>
							<xsl:value-of select="name" />
							
							<xsl:if test="name/@msg">
								<em><xsl:value-of select="name/@msg" /></em>
							</xsl:if>
						</td>
						<td>
							<xsl:value-of select="country" />

							<xsl:if test="country/@msg">
								<em><xsl:value-of select="country/@msg" /></em>
							</xsl:if>
						</td>
						<td>
							<xsl:value-of select="city" />

							<xsl:if test="city/@msg">
								<em><xsl:value-of select="city/@msg" /></em>
							</xsl:if>
						</td>
						<td>
							<xsl:value-of select="postcode" />

							<xsl:if test="postcode/@msg">
								<em><xsl:value-of select="postcode/@msg" /></em>
							</xsl:if>
						</td>
						<td>
							<xsl:value-of select="telephone" />

							<xsl:if test="telephone/@msg">
								<em><xsl:value-of select="telephone/@msg" /></em>
							</xsl:if>
						</td>
						<td>
							<xsl:value-of select="email" />

							<xsl:if test="email/@msg">
								<em><xsl:value-of select="email/@msg" /></em>
							</xsl:if>
						</td>
						<td>
							<xsl:if test="@msg">
								<em><xsl:value-of select="@msg" /></em>
							</xsl:if>
						</td>
					</tr>
				</xsl:for-each>
			</tbody>
		</table>
	</xsl:template>
</xsl:stylesheet>
