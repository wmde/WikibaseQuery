( function( $, query, dv, snakview ) {
	'use strict';

	var SimpleQueryForm = query.SimpleQueryForm = function( $form ) {
		this.$form = $form;
	};

	SimpleQueryForm.prototype.enhance = function( options ) {
		var $snakview, _snakview, self = this;

		$snakview = $( '<div/>' );
		_snakview = $snakview.snakview( {
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
			self.inputFieldValue( _snakview.value() );
		} );

		_snakview.startEditing();
	};

	SimpleQueryForm.prototype.inputFieldValue = function( newValue ) {
		var $valueJson = this.$form.find( '[name="valuejson"]' ),
			$property = this.$form.find( '[name="property"]' );

		if( typeof newValue === 'undefined' ) {
			return this._getInputFieldValue( $valueJson, $property );
		} else {
			return this._setInputFieldValue( $valueJson, $property, newValue );
		}
	};

	SimpleQueryForm.prototype._getInputFieldValue = function( $valueJson, $property ) {
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
	};

	SimpleQueryForm.prototype._setInputFieldValue = function( $valueJson, $property, newValue ) {
		var dataValue = newValue.datavalue;
		$property.val( newValue.property );
		$valueJson.val(  $.toJSON( {
			value: dataValue && dataValue.toJSON(),
			type: dataValue && dataValue.getType()
		} ) );
	};

}( jQuery, wikibase.query, dataValues, jQuery.wikibase.snakview, wikibase.store.EntityStore ) );
