<?php

namespace Wikibase\Query;

use InvalidArgumentException;
use Serializers\Serializer;
use Wikibase\Entity;

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

		if( !( $queryEntity instanceof QueryEntity ) ){
			throw new InvalidArgumentException('Not instance of queryEntity');
		}

		$querySerialization = $this->querySerializer->serialize( $queryEntity );

		return array(
			'entity' => $this->getSerializedId( $queryEntity ),
			'description' => $queryEntity->getDescriptions(),
			'query' => $querySerialization,
		);
	}

	private function getSerializedId( Entity $entity ) {
		$id = $entity->getId();

		if ( $id === null ) {
			return $id;
		}
		else {
			return array( $id->getEntityType(), $id->getNumericId() );
		}
	}

	// TODO

}
