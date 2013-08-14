/**
 * Main WP Twinfield Sync Module.
 * 
 * Has common functions and dom elements used between all
 * submodules.
 * 
 * @author Leon Rowland <leon@rowland.nl>
 * @type object
 */
var WP_Twinfield_Sync = {
	
	/**
	 * Holds all configuration options
	 * @type object
	 */
	config: {
		
		/**
		 * Holds all common DOM elements used
		 * in all submodules.
		 * @type object
		 */
		dom:{}
	}
	
	/**
	 * Prepares the DOM object with the common elements.
	 * 
	 * @returns void
	 */
	, ready: function() {
		WP_Twinfield_Sync.config.dom.postID = jQuery('#post_ID');
	}
	
	/**
	 * Returns the spinner image. The spinner url is retrieved from
	 * the WP_Twinfield_Vars object.
	 * 
	 * @returns {Image}
	 */
	, getSpinnerHTML: function() {
		var img = new Image();
		img.src = WP_Twinfield_Vars.spinner;
		return img;
	}
	
	/**
	 * Adds the spinner to the passed in jquery
	 * objects html.
	 * 
	 * @param jQuery object element
	 * @returns void
	 */
	, startSpinner: function(element) {
		element.html(WP_Twinfield_Sync.getSpinnerHTML());
	}
	
	/**
	 * Emptys the jquerys object html.
	 * 
	 * @param jQuery object element
	 * @returns void
	 */
	, stopSpinner: function(element) {
		element.empty();
	}
};

/**
 * Invoice Submodule for Twinfield Sync.
 * 
 * Holds all related methods to do with synchronizing the Invoice
 * metabox.
 * 
 * @type object
 */
WP_Twinfield_Sync.invoice = {
	
	/**
	 * Holds all the configuration options for
	 * the invoice submodule.
	 * @type object
	 */
	config: {
		
		/**
		 * Holds all the DOM elements used in the 
		 * invoice submodule
		 * @type object
		 */
		dom: {}
	}
	
	/**
	 * Prepares the DOM object with the elements for
	 * this sub module.  Prepares the event listeners.
	 * 
	 * @returns void
	 */
	, ready: function() {
		WP_Twinfield_Sync.ready();
		
		WP_Twinfield_Sync.invoice.config.dom.messageHolder = jQuery('#TwinfieldInvoiceMetaBoxSync_MessageHolder');
		WP_Twinfield_Sync.invoice.config.dom.spinnerHolder = jQuery('#TwinfieldInvoiceMetaBoxSync_SpinnerHolder');
		
		WP_Twinfield_Sync.invoice.config.dom.customerID = jQuery('#TwinfieldInvoiceMetaBoxSync_CustomerID');
		WP_Twinfield_Sync.invoice.config.dom.invoiceID = jQuery('#TwinfieldInvoiceMetaBoxSync_InvoiceID');
		WP_Twinfield_Sync.invoice.config.dom.invoiceType = jQuery('#TwinfieldInvoiceMetaBoxSync_InvoiceType');
		
		WP_Twinfield_Sync.invoice.config.dom.syncButton = jQuery('#TwinfieldInvoiceMetaBoxSync_SyncButton');
		
		WP_Twinfield_Sync.invoice.binds();
	}
	
	/**
	 * Attachs the event listeners.  Just attaches to the sync button
	 * for now.
	 * 
	 * @returns void
	 */
	, binds: function() {
		WP_Twinfield_Sync.invoice.config.dom.syncButton.click(WP_Twinfield_Sync.invoice.sync);
	}
	
	/**
	 * Makes the ajax request to synchronize the invoice
	 * order.  Prevents the default event action from the 
	 * event trigger.
	 * 
	 * @param {Event} e
	 * @returns void
	 */
	, sync: function(e) {
		e.preventDefault();
		
		WP_Twinfield_Sync.invoice.clearMessagesHolder();
		WP_Twinfield_Sync.startSpinner(WP_Twinfield_Sync.invoice.config.dom.spinnerHolder);
		
		jQuery.ajax({
			type: 'POST'
			, url: ajaxurl
			, dataType: 'json'
			, data : {
				action: 'twinfield_invoice_metabox_sync'
				,post_id: WP_Twinfield_Sync.config.dom.postID.val()
				,customer_id: WP_Twinfield_Sync.invoice.config.dom.customerID.val()
				,invoice_id: WP_Twinfield_Sync.invoice.config.dom.invoiceID.val()
				,invoice_type: WP_Twinfield_Sync.invoice.config.dom.invoiceType.val()
			}
			, success: WP_Twinfield_Sync.invoice.syncSuccess
			, error: WP_Twinfield_Sync.invoice.syncError
			
		});
	}
	
	/**
	 * Called if the ajax sync request was successfully made. Unsure of the state
	 * of the actual request we look for the .ret key which tells us the return
	 * state of either true or false.  Setting the success or error messages
	 * respectively.
	 * 
	 * @param {json object} data
	 * @returns void
	 */
	, syncSuccess: function(data) {
		WP_Twinfield_Sync.stopSpinner(WP_Twinfield_Sync.invoice.config.dom.spinnerHolder);
		
		if(true === data.ret) {
			WP_Twinfield_Sync.invoice.setSuccessMessage(data.msg);
		} else {
			WP_Twinfield_Sync.invoice.setErrorMessages(data.msgs);
		}
	}
	
	/**
	 * Error on AJAX. Will be an empty method.
	 * 
	 * @returns {undefined}
	 */
	, syncError: function(one,two,three) {}
	
	/**
	 * Emptys the existing messages from the metabox.
	 * 
	 * @returns void
	 */
	, clearMessagesHolder: function() {
		WP_Twinfield_Sync.invoice.config.dom.messageHolder.empty();
	}

	/**
	 * Sets a success error message to the message holder.
	 * 
	 * @param string successMessage
	 * @returns void
	 */
	, setSuccessMessage: function(successMessage) {
		var successMessageDom = jQuery('<div class="updated"></div>');
		successMessageDom.html(jQuery('<p></p>').html(successMessage));
		
		WP_Twinfield_Sync.invoice.config.dom.messageHolder.append(successMessageDom);
	}

	/**
	 * Sets the errors messages to the message holder
	 * The passed errorMessages are an array.
	 * 
	 * @param {type} errorMessages
	 * @returns void
	 */
	, setErrorMessages: function(errorMessages) {
		var errorMessagesDom = jQuery('<div></div>');

		jQuery.each(errorMessages, function(i, data) {
			var errorMessageDom = jQuery('<div class="error"></div>');
			errorMessageDom.html(jQuery('<p></p>').html(data));

			errorMessagesDom.append(errorMessageDom);
		});
		
		WP_Twinfield_Sync.invoice.config.dom.messageHolder.append(errorMessagesDom);
	}
};