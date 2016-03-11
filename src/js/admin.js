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
