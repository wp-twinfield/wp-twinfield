<script type="text/template" id="twinfield-customer-search-view-template">
	<span class="twinfield-code"><%= code %></span>
	<span class="twinfield-name"><%= name %></span>
</script>

<script type="text/template" id="twinfield-customer-modal-view-template">
	<div class="twinfield-modal-dialog" tabindex="0">
		<div class="twinfield-modal">
			<div class="twinfield-modal-content">

				<div class="twinfield-modal-header">
					<button class="twinfield-modal-close modal-close modal-close-link dashicons dashicons-no-alt">
						<span class="screen-reader-text">Close modal panel</span>
					</button>

					<h1>Twinfield klant</h1>
				</div>

				<ul class="twinfield-nav twinfield-nav-tabs">
					<li class="active">
						<a href="#">Klant zoeken</a>
					</li>
					<li>
						<a href="#">Klant toevoegen</a>
					</li>
				</ul>

				<article>
					<div class="twinfield-customers-search">
						<div class="twinfield-customers-search-fields">
							Zoeken <input class="twinfield-customers-search-input" type="text" /> <span class="spinner"></span>
						</div>

						<div class="twinfield-customers-list">

						</div>
					</div>
				</article>

				<footer>
					<div class="inner">
						<button class="twinfield-select-customer button button-primary button-large">Select Customer</button>
					</div>
				</footer>
			</div>

		</div>

		<div class="twinfield-modal-backdrop modal-close"></div>
	</div>
</script>

<style type="text/css">

</style>

<?php

wp_enqueue_script( 'backbone' );

?>
<script type="text/javascript">

</script>
