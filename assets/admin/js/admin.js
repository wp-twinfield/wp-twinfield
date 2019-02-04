jQuery( document ).ready( function( $ ) {
	$( '.twinfield-customer-select' ).select2( {
		minimumInputLength: 2,
		allowClear: true,
		placeholder: '',
		ajax: {
			url: twinfield.ajax.customers,
			dataType: 'json',
			data: function (params) {
				return {
					search: params.term
				}
			},
			processResults: function (data) {
				return {
					results: jQuery.map( data, function( obj ) {
						return {
							id: obj.code,
							text: `${obj.code} - ${obj.name}`
						};
					} )
				}
			}
		}
	} );
} );
