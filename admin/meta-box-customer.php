<?php wp_nonce_field( 'twinfield_customer', 'twinfield_customer_nonce' ); ?>

<table class="form-table">
	<tr>
		<th scope="row">
			<label for="twinfield_customer_id"><?php esc_html_e( 'Customer ID', 'twinfield' ); ?></label>
		</th>
		<td>
			<input type="text" id="twinfield_customer_id" name="twinfield_customer_id" value="<?php echo esc_attr( $twinfield_customer_id ); ?>" />
		</td>
	</tr>
</table>

<?php

wp_enqueue_script( 'backbone' );

?>
<script type="text/javascript">
	jQuery( document ).ready( function( $ ) {
		var twinfield = {};

		twinfield.Customer = Backbone.Model.extend( {
			defaults: {
				code: '',
				name: ''
			}
		} );

		twinfield.Customers = Backbone.Collection.extend( {
			model: twinfield.Customer,
			url: ajaxurl
		} );

		twinfield.CustomerSearchView = Backbone.View.extend( {
			tagName:  'li',

			template: _.template( $( '#twinfield-customer-search-view-template' ).html() ),

			render: function() {
				this.$el.html(this.template(this.model.toJSON()));

				return this;
			}
		} );

		twinfield.CustomersSearchView = Backbone.View.extend( {
			el: '.twinfield-customers-search',

			template: _.template( '<h3>Hello <%= who %></h3>' ),

			events: {
				'keypress .twinfield-customers-search-input': 'searchOnEnter',
				'click .clear-completed': 'clearCompleted',
				'click .toggle-all': 'toggleAllComplete'
			},

			initialize: function() {
				this.customers = new twinfield.Customers();

				this.$input = this.$( '.twinfield-customers-search-input' );
				this.$list  = this.$( '.twinfield-customers-list' );

				this.listenTo( this.customers, 'add', this.addOne );
				this.listenTo( this.customers, 'reset', this.addAll );

				this.render();
			},

			render: function() {
				
			},

			addOne: function (customer) {
				var view = new twinfield.CustomerSearchView( { model: customer } );

				this.$list.append( view.render().el );
			},

			addAll: function () {
				this.$list.html( '' );

				this.customers.each( this.addOne, this );
			},

			searchOnEnter: function( e ) {
				if ( 13 === e.which && this.$input.val().trim() ) {
					this.customers.fetch( {
						reset: true,
						data: { action: 'twinfield_search_customers' }
					} );

					return false;
				}
			}
		} );

		$( '.twinfield-customers-search' ).each( function() {
			return new twinfield.CustomersSearchView( { el: this } );
		} );

	} );
</script>

<div class="twinfield-customers-search">
	Zoeken <input class="twinfield-customers-search-input" type="text" />

	<div class="twinfield-customers-list">

	</div>
</div>

<div class="twinfield-customers-search">
	Zoeken <input class="twinfield-customers-search-input" type="text" />

	<div class="twinfield-customers-list">

	</div>
</div>

<script type="text/template" id="twinfield-customer-search-view-template">
	<%= code %>
	<%= name %>
</script>
