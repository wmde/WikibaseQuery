( function( $, query, dv, snakview ) {
	'use strict';

	var SimpleQueryForm = query.SimpleQueryForm = function( $form ) {
		this.$form = $form;
	};

	$.extend( SimpleQueryForm.prototype, {
		/**
		 * @var jQuery $form
		 */
		$form: null,

		/**
		 * Replace the form's input fields with a snakview
		 *
		 * @param Object options An object holding options for the snakview.
		 *                       Currently, only options.entityStore is used.
		 */
		enhance: function( options ) {
			var $snakview,
				snakview,
				self = this;

			$snakview = $( '<div/>' );
			snakview = $snakview.snakview( {
				entityStore: options.entityStore,
				value: this.inputFieldValue(),
				autoStartEditing: false,
				locked: { snaktype: true }
			} ).data( 'snakview' );

			$snakview.on( 'snakviewafterstopediting', function() {
				self.$form.submit();
			} );

			// Don't leave space for a toolbar
			$snakview.addClass( 'wb-claim-mainsnak' ).css( 'margin-right', '0' );

			this.$form.find( '.wb-claim' )
				.append( $snakview.hide() )
				.children().toggle();

			this.$form.on( 'submit', function() {
				self.inputFieldValue( snakview.value() );
			} );

			snakview.startEditing();
		},

		/**
		 * Get or set the current value of the field
		 *
		 * The argument and the return value have the following type:
		 * { property: String, datavalue: DataValue }
		 * If the argument is not given, the current value is returned.
		 *
		 * @param [Object] newValue Optional value to set
		 */
		inputFieldValue: function( newValue ) {
			var $valueJson = this.$form.find( '[name="valuejson"]' ),
				$property = this.$form.find( '[name="property"]' );

			if( typeof newValue === 'undefined' ) {
				return this._getInputFieldValue( $valueJson, $property );
			} else {
				return this._setInputFieldValue( $valueJson, $property, newValue );
			}
		},

		_getInputFieldValue: function( $valueJson, $property ) {
			var dataValue;
			try {
				dataValue = $.parseJSON( $valueJson.val() );
				dataValue = dv.newDataValue( dataValue.type, dataValue.value );
			} catch( e ) {
				dataValue = null;
			}
			return {
				property: $property.val(),
				datavalue: dataValue
			};
		},

		_setInputFieldValue: function( $valueJson, $property, newValue ) {
			var dataValue = newValue.datavalue;
			$property.val( newValue.property );
			$valueJson.val(  $.toJSON( {
				value: dataValue && dataValue.toJSON(),
				type: dataValue && dataValue.getType()
			} ) );
		}
	} );

}( jQuery, wikibase.query, dataValues, jQuery.wikibase.snakview ) );
