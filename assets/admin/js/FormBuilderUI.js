/**
 * FormBuilderUI
 * 
 * Is loaded on form builder pages and can be used by the javascript of those pages to add new
 * rows to the FormBuilder.  Supply jFormBuilderUI_Row to the tbody element of a table, and call
 * addRow on a button and it will duplicate the row and increment numbers.
 * 
 * Methods:
 * 
 * load:func | Call before doing any other method. Will ensure that the instance of FormBuilderUI is the latest
 * addRow:func | Call on a click or response from data. Will duplicate all rows where jFormBuilderUI_Row is a parent of
 * nextNumber:func | Returns the next new row number
 * 
 * @author Leon Rowland <leon@rowland.nl>
 * @type object literal
 * @version 1.0.0
 */
var FormBuilderUI = {
	
	/**
	 * Holds all configuration options
	 * @type object
	 */
	config: {
		
		/**
		 * All DOM elements used in FormBuilderUI
		 * @type object
		 */
		 dom: {}
		 
		 /**
		  * Holds the lastLine from the table.
		  * @type jquery object
		  */
		,lastLine: {}
	}

	/**
	 * Refreshes the instance of this FormBuilderUI.  Resets all variable
	 * references.
	 * 
	 * @returns void
	 */
	,load: function() {
		FormBuilderUI.config.dom.linesRow = jQuery('.jFormBuilderUI_TableBody');

		FormBuilderUI.config.lastLine.element = FormBuilderUI.config.dom.linesRow.children('tr').last();
		FormBuilderUI.config.lastLine.number = FormBuilderUI.config.lastLine.element.data('number');
	}

	/**
	 * Adds a row to table.  Will clone all input and textarea
	 * @returns void
	 */
	,addRow: function() {

		var clone = FormBuilderUI.config.lastLine.element.clone();

		clone.data('number', FormBuilderUI.nextNumber());
		
		clone.appendTo('.jFormBuilderUI_TableBody').find('input, textarea').each(FormBuilderUI.callbacks.incrementRowElements);

	}

	/**
	 * Returns the incremented number for new rows
	 * @returns int
	 */
	,nextNumber: function() {
		return FormBuilderUI.config.lastLine.number + 1;
	}
	
	/**
	 * ===========================
	 * 
	 * END OF PUBLIC USED METHODS
	 * 
	 * ===========================
	 */

	/**
	 * Holds all callbacks for other methods in FormBuilderUI
	 * 
	 * @type object
	 */
	,callbacks: {
		
		/**
		 * Callback method for addRow.each line.
		 * 
		 * Changes all elements in the row to have the new number.
		 * 
		 * @param int index
		 * @returns void
		 */
		incrementRowElements: function(index) {
			var self = jQuery(this);
			var currentName = self.attr('name');
			var newName = currentName.replace(FormBuilderUI.config.lastLine.number, FormBuilderUI.nextNumber());
			
			self.attr('name', newName);
			self.attr('value', '');
		}
	}
};