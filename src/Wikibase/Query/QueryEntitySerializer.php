<?php

namespace Wikibase\Query;

use Serializers\Serializer;

/**
 * @since 1.0
 *
 * @file
 * @ingroup WikibaseQuery
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 * @author Adam Shorland < adamshorland@gmail.com >
 */
class QueryEntitySerializer {

	protected $querySerializer;

	public function __construct( Serializer $querySerializer ) {
		$this->querySerializer = $querySerializer;
	}

	public function serialize( $queryEntity ) {
		$querySerialization = $this->querySerializer->serialize( $queryEntity );

		return array(
			'query' => $querySerialization
		);
	}

	// TODO

}
