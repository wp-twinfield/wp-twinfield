<table class="form-table">
	<tr>
		<th scope="row">
			<label for="twinfield_customer_id"><?php esc_html_e( 'Customer ID', 'twinfield' ); ?></label>
		</th>
		<td>
			<div class="twinfield-customer-field">
				<input type="text" id="twinfield_customer_id" name="twinfield_customer_id" value="<?php echo esc_attr( $twinfield_customer_id ); ?>" />

				<span class="twinfield-customer-select dashicons dashicons-search" />
			</div>
		</td>
	</tr>
</table>

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
.twinfield-nav {
	list-style: none;
}

.twinfield-nav:after {
	clear: both;
	display: table;
    content: " ";
}

.twinfield-nav-tabs {
	border-bottom: 1px solid #ddd;
}

.twinfield-nav > li > a {
	color: #0073aa;

	display: block;

	font-size: 14px;

	line-height: 18px;
    
    text-decoration: none;

	padding: 10px 15px;

	position: relative;
}

.twinfield-nav .active {
    margin: -1px -1px 0;
    background: #fff;
    border: 1px solid #ddd;
    border-bottom: none;
}

.twinfield-nav-tabs > li {
	float: left;

    margin-bottom: -1px;
}

.twinfield-modal-close {
	position: absolute;

	top: 0;
	right: 0;
}

.twinfield-modal-content {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    overflow: auto;
    min-height: 300px;
    -webkit-box-shadow: 0 5px 15px rgba(0,0,0,.7);
    box-shadow: 0 5px 15px rgba(0,0,0,.7);
    background: #fcfcfc;
    -webkit-font-smoothing: subpixel-antialiased;
}

.twinfield-router .active,
.twinfield-router > a.active:last-child {
    margin: -1px -1px 0;
    background: #fff;
    border: 1px solid #ddd;
    border-bottom: none;
}

	.twinfield-frame-title {
		top: 0;

		height: 50px;
	}

	.twinfield-frame-title h1 {
		padding: 0 16px;

		font-size: 22px;

		line-height: 50px;

		margin: 0;
	}

	.twinfield-frame-router {
		top: 50px;
		height: 36px;

		position: absolute;

		right: 0;

		z-index: 200;
	}

	.twinfield-customer-select {
		cursor: pointer;

		vertical-align: text-bottom;
	}

	.twinfield-modal-main {
		padding-bottom: 51px;
	}

	.twinfield-modal-main .twinfield-modal-header {
		background: #fcfcfc;
		border-bottom: 1px solid #ddd;

		height: 50px;

		padding: 0 50px 0 16px;
	}

	.twinfield-modal-main .twinfield-modal-header h1 {
		margin: 0;

		font-size: 18px;
		font-weight: 700;

		line-height: 50px;
	}

	.twinfield-modal-main .twinfield-modal-header .modal-close-link {
		cursor: pointer;
		color: #777;
		height: 50px;
		width: 50px;
		padding: 0;
		position: absolute;
		top: 0;
		right: 0;
		text-align: center;
		border: 0;
		border-left: 1px solid #ddd;
		background-color: transparent;
		-webkit-transition: color .1s ease-in-out,background .1s ease-in-out;
		transition: color .1s ease-in-out,background .1s ease-in-out;
	}

	.twinfield-modal-main .twinfield-modal-header .modal-close-link:focus,
	.twinfield-modal-main .twinfield-modal-header .modal-close-link:hover {
		background: #ddd;

		border-color: #ccc;

		color: #000;
	}

	.twinfield-modal-main article {
		padding: 10px 16px;
	}

	.twinfield-modal-main footer {
		position: absolute;
		left: 0;
		right: 0;
		bottom: 0;
		z-index: 100;
		padding: 10px 16px;
		background: #fcfcfc;
		border-top: 1px solid #dfdfdf;
		box-shadow: 0 -4px 4px -4px rgba(0,0,0,.1);
	}

	.twinfield-modal-main footer .inner {
		float: right;
	}

	.twinfield-modal-backdrop {
		position: fixed;

		top: 0;
		left: 0;
		right: 0;
		bottom: 0;

		min-height: 360px;

		background: #000;
		
		opacity: .7;

		z-index: 99900;
	}

	.twinfield-modal .twinfield-modal-content {
		position: fixed;

		top: 50%;
		left: 50%;

		width: 800px;

		background: #fff;

		z-index: 100000;
	}

	.twinfield-customers-search-fields {
		margin-bottom: 10px;
	}

	.twinfield-customers-list {
		background: #fff;

		border: 1px solid #dfdfdf;

		list-style: none;

		margin: 0;
		padding: 0;
	}

	.twinfield-customers-list li {
		border-bottom: 1px solid #f1f1f1;

		color: #32373c;
		clear: both;

		margin-bottom: 0;
		padding: 4px 6px 4px 10px;

		position: relative;
	}

	.twinfield-customers-list li .twinfield-code {
		text-transform: uppercase;

		color: #666;

		font-size: 11px;

		position: absolute;
		right: 5px;
		top: 5px;
	}

	.twinfield-customers-list li .twinfield-name {
		display: inline-block;

		width: 80%;
		width: calc( 100% - 68px );

		word-wrap: break-word;
	}


	.twinfield-customers-list li:nth-child(odd) {
		background-color: #f9f9f9;
	}

	.twinfield-customers-list li:hover {
		background: #eaf2fa;

		color: #151515;
	}

	.twinfield-customers-list li.selected {
		background: #ddd;

		color: #32373c;
	}
</style>

<?php

wp_enqueue_script( 'backbone' );

?>
<script type="text/javascript">
	jQuery( document ).ready( function( $ ) {
		var twinfield = {};

		twinfield.Customer = Backbone.Model.extend( {
			defaults: {
				code: '',
				name: '',
				selected: false
			},

			toggleSelected: function () {
				this.save( {
					selected: ! this.get( 'selected' )
				} );
			}
		} );

		twinfield.Customers = Backbone.Collection.extend( {
			model: twinfield.Customer,
			url: ajaxurl
		} );

		// @see https://cdnjs.com/libraries/backbone.js/tutorials/what-is-a-view
		// @see https://github.com/woothemes/woocommerce/blob/2.5.3/assets/js/admin/order-backbone-modal.js
		// @see http://danialk.github.io/blog/2013/04/07/backbone-tips-rendering-views-and-their-childviews/
		// @see https://addyosmani.com/backbone-fundamentals/#rendering-view-hierarchies
		// @see https://github.com/WordPress/WordPress/blob/4.4.2/wp-includes/js/media-views.js
		// @see https://github.com/WordPress/WordPress/blob/4.4.2/wp-includes/js/media-views.js#L5929-L5945
		twinfield.CustomerModalView = Backbone.View.extend( {
			tagName: 'div',

			id: 'twinfield-modal-dialog',

			className: "hidden",

			template: _.template( $( '#twinfield-customer-modal-view-template' ).html() ),

			events: {
				'click .modal-close': 'closeHandler',
				'click .twinfield-select-customer': 'selectHandler',
			},

			initialize: function() {
				this.render();

				this.searchView = new twinfield.CustomersSearchView( { el: this.$( '.twinfield-customers-search' ) } );

				this.listenTo( this.searchView, 'select', this.select );
			},

			select: function( customer ) {
				this.selectedCustomer = customer;
			},

			render: function() {
				this.$el.html( this.template() );

				$( document.body ).append( this.$el );

				this.$( '.twinfield-modal-content' ).css( {
					'margin-top': '-' + ( $( '.twinfield-modal-content' ).height() / 2 ) + 'px',
					'margin-left': '-' + ( $( '.twinfield-modal-content' ).width() / 2 ) + 'px'
				} );

				return this;
			},

			closeHandler: function( e ) {
				e.preventDefault();

				// this.undelegateEvents();

				// this.remove();

				this.hide();
			},

			selectHandler: function( e ) {
				this.trigger( 'select', this.selectedCustomer );

				this.closeHandler( e );
			},

			show: function() {
				this.$el.removeClass( 'hidden' );
			},

			hide: function() {
				this.$el.addClass( 'hidden' );
			}
		} );

		twinfield.CustomerInputFieldView = Backbone.View.extend( {
			initialize: function() {
				this.$input = this.$( 'input' );

				this.modal = new twinfield.CustomerModalView();

				this.render();
			},

			events: {
				'click .twinfield-customer-select': 'selectCustomer'
			},

			selectCustomer: function() {
				this.modal.show();

				this.listenTo( this.modal, 'select', this.select );

				return false;
			},

			select: function( customer ) {
				this.$input.val( customer.get( 'code' ) );

				this.modal.hide();
			},

			render: function() {
				console.log( 'twinfield.CustomerInputFieldView.render' );

				return this;
			}
		} );

		twinfield.CustomerSearchView = Backbone.View.extend( {
			tagName:  'li',

			template: _.template( $( '#twinfield-customer-search-view-template' ).html() ),
			
			events: {
				'click': 'toggleSelected'
			},

			initialize: function () {

				this.listenTo( this.model, 'change', this.render );
			},

			render: function() {
				this.$el.html( this.template( this.model.toJSON() ) );
				this.$el.toggleClass( 'selected', this.model.get( 'selected' ) );

				return this;
			},

			// Toggle the `"completed"` state of the model.
			toggleSelected: function () {
				this.model.toggleSelected();
			},
		} );

		twinfield.CustomersSearchView = Backbone.View.extend( {
			el: '.twinfield-customers-search',

			events: {
				'keypress .twinfield-customers-search-input': 'searchOnEnter'
			},

			initialize: function() {
				this.customers = new twinfield.Customers();

				this.$input   = this.$( '.twinfield-customers-search-input' );
				this.$list    = this.$( '.twinfield-customers-list' );
				this.$spinner = this.$( '.spinner' );

				this.listenTo( this.customers, 'add', this.addOne );
				this.listenTo( this.customers, 'reset', this.addAll );
				this.listenTo( this.customers, 'change:selected', this.selectCustomer );

				this.render();
			},

			selectCustomer: function( customer ) {
				if ( this.selectedCustomer ) {
					this.selectedCustomer.set( 'selected', false );
				}

				this.selectedCustomer = customer;

				this.trigger( 'select', customer );
			},

			addOne: function( customer ) {
				var view = new twinfield.CustomerSearchView( { model: customer } );

				this.$list.append( view.render().el );
			},

			addAll: function () {
				this.$list.html( '' );

				this.customers.each( this.addOne, this );

				this.$spinner.removeClass( 'is-active' );
			},

			searchOnEnter: function( e ) {
				if ( 13 === e.which && this.$input.val().trim() ) {
					// @see https://github.com/WordPress/WordPress/blob/4.3.1/wp-includes/js/wplink.js#L439
					this.$spinner.addClass( 'is-active' );

					this.customers.fetch( {
						reset: true,
						data: {
							action: 'twinfield_search_customers',
							search: this.$input.val()
						}
					} );

					return false;
				}
			}
		} );

		$( '.twinfield-customers-search' ).each( function() {
			return new twinfield.CustomersSearchView( { el: this } );
		} );

		$( '.twinfield-customer-field' ).each( function() {
			return new twinfield.CustomerInputFieldView( { el: this } );
		} );

	} );
</script>
