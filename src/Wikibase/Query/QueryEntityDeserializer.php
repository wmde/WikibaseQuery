<?php

namespace Wikibase\Query;

use Deserializers\Deserializer;
use Deserializers\Exceptions\DeserializationException;

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
class QueryEntityDeserializer {

	protected $queryDeserializer;

	public function __construct( Deserializer $queryDeserializer ) {
		$this->queryDeserializer = $queryDeserializer;
	}

	public function deserialize( $serialization ) {
		$this->assertCanSerialize( $serialization );

		$query = $this->queryDeserializer->deserialize( $serialization['query'] );
		$queryEntity = new QueryEntity( $query );

		return $queryEntity;
	}

	protected function assertCanSerialize( $serialization ) {
		if( !$this->canDeserialize( $serialization ) ) {
			throw new DeserializationException( 'Cannot deserialize.' );
		}
	}

	public function canDeserialize( $serialization ) {
		if( !is_array( $serialization ) || !array_key_exists( 'query', $serialization ) ) {
			return false;
		}

		return $this->queryDeserializer->canDeserialize( $serialization['query'] );
	}

	// TODO: finish implementation

}
