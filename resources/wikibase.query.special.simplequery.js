( function( $, SimpleQueryForm, EntityStore ) {
	'use strict';

	var simpleQueryForm = new SimpleQueryForm( $( '#wb-specialsimplequery-form' ) );

	simpleQueryForm.enhance( {
		entityStore: new EntityStore()
	} );

}( jQuery, wikibase.query.SimpleQueryForm, wikibase.store.EntityStore ) );
