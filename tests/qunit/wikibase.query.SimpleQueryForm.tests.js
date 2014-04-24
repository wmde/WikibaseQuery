/**
 * @licence GNU GPL v2+
 * @author Adrian Lang < adrian.lang@wikimedia.de >
 */

( function( $, SimpleQueryForm, StringValue, EntityStore, QUnit, CompletenessTest ) {
	'use strict';

	function getSimpleQueryFormHtml() {
		var $form = $( '<form/>' );

		$( '<div/>' )
			.addClass( 'wb-claim' )
			.append( '<input name="valuejson">')
			.append( '<input name="property">')
			.appendTo( $form );

		return $form;
	}

	QUnit.module( 'wikibase.query.SimpleQueryForm' );

	if( QUnit.urlParams.completenesstest ) {
		new CompletenessTest( SimpleQueryForm );
	}

	QUnit.test( 'Constructor', function( assert ) {
		var simpleQueryForm = new SimpleQueryForm();

		assert.ok( simpleQueryForm instanceof SimpleQueryForm );
	} );

	QUnit.test( 'Constructor with form', function( assert ) {
		var $simpleQueryForm = getSimpleQueryFormHtml();
		var simpleQueryForm = new SimpleQueryForm( $simpleQueryForm );

		assert.ok( simpleQueryForm instanceof SimpleQueryForm );
	} );

	QUnit.test( 'inputFieldValue as getter without fields', function( assert ) {
		var $simpleQueryForm = $( '<div/>' );
		var simpleQueryForm = new SimpleQueryForm( $simpleQueryForm );
		var inputFieldValue = simpleQueryForm.inputFieldValue();

		assert.ok( !inputFieldValue.property );
		assert.ok( !inputFieldValue.datavalue );
	} );

	QUnit.test( 'inputFieldValue as getter with invalid value', function( assert ) {
		var $simpleQueryForm = getSimpleQueryFormHtml();
		var simpleQueryForm = new SimpleQueryForm( $simpleQueryForm );

		$simpleQueryForm.find( '[name="valuejson"]' ).val( '{"key":"value"}' );
		$simpleQueryForm.find( '[name="property"]' ).val( 'P1' );

		var inputFieldValue = simpleQueryForm.inputFieldValue();

		assert.equal( inputFieldValue.property, 'P1' );
		assert.ok( !inputFieldValue.datavalue );
	} );

	QUnit.test( 'inputFieldValue as getter with well-formed content', function( assert ) {
		var $simpleQueryForm = getSimpleQueryFormHtml();
		var simpleQueryForm = new SimpleQueryForm( $simpleQueryForm );

		$simpleQueryForm.find( '[name="valuejson"]' ).val( '{"type":"string","value": "test"}' );
		$simpleQueryForm.find( '[name="property"]' ).val( 'P1' );

		assert.deepEqual( simpleQueryForm.inputFieldValue(), {
				property: 'P1',
				datavalue: new StringValue( 'test' )
		} );
	} );

	QUnit.test( 'inputFieldValue as setter' , function( assert ) {
		var $simpleQueryForm = getSimpleQueryFormHtml();
		var simpleQueryForm = new SimpleQueryForm( $simpleQueryForm );

		$simpleQueryForm.find( '[name="valuejson"]' ).val( '{"type":"string","value": "test"}' );
		$simpleQueryForm.find( '[name="property"]' ).val( 'P1' );

		var newValue = {
			property: 'P2',
			datavalue: new StringValue( 'test2' )
		};
		var newDataValueSerialization = {type:'string', value:'test2'};

		simpleQueryForm.inputFieldValue( newValue );
		assert.deepEqual( simpleQueryForm.inputFieldValue(), newValue );
		assert.deepEqual(
			$.parseJSON( $simpleQueryForm.find( '[name="valuejson"]' ).val() ),
			newDataValueSerialization
		);
		assert.equal(
			$simpleQueryForm.find( '[name="property"]' ).val(),
			newValue.property
		);
	} );

	QUnit.test( 'enhance', function( assert ) {
		var $simpleQueryForm = getSimpleQueryFormHtml();
		var simpleQueryForm = new SimpleQueryForm( $simpleQueryForm );

		simpleQueryForm.enhance( {
			entityStore: new EntityStore()
		} );

		assert.equal( $simpleQueryForm.find( '.wb-snakview' ).length, 1 );
	} );

} )( jQuery, wikibase.query.SimpleQueryForm, dataValues.StringValue, wikibase.store.EntityStore, QUnit, CompletenessTest );
