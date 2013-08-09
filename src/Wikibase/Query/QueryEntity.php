<?php

namespace Wikibase\Query;

use Ask\DeserializerFactory;
use Ask\Language\Query;
use Ask\SerializerFactory;
use InvalidArgumentException;
use MWException;
use RuntimeException;
use Wikibase\Entity;
use Wikibase\Repo\WikibaseRepo;

/**
 * Represents a single Wikibase query.
 *
 * @since 0.1
 *
 * @file
 * @ingroup WikibaseDataModel
 * @ingroup WikibaseQuery
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class QueryEntity extends Entity {

	const ENTITY_TYPE = 'query';

	/**
	 * @since 0.1
	 *
	 * @var Query
	 */
	protected $query = null;

	public function __construct( Query $query ) {
		$this->query = $query;

		parent::__construct( array() );
	}

	/**
	 * Returns the Query of the query entity.
	 *
	 * @since 0.1
	 *
	 * @return Query
	 * @throws RuntimeException
	 */
	public function getQuery() {
		return $this->query;
	}

	/**
	 * Sets the Query of the query entity.
	 *
	 * @since 0.1
	 *
	 * @param Query $queryDefinition
	 */
	public function setQuery( Query $queryDefinition ) {
		$this->query = $queryDefinition;
	}

	/**
	 * @see Entity::getType
	 *
	 * @since 0.1
	 *
	 * @return string
	 */
	public function getType() {
		return QueryEntity::ENTITY_TYPE;
	}

//	/**
//	 * @see Entity::stub
//	 */
//	public function stub() {
//		parent::stub();
//
//		if ( $this->query !== null ) {
//			$serializerFactory = new SerializerFactory();
//
//			$this->data['query'] = $serializerFactory->newQuerySerializer()->serialize( $this->query );
//			$this->query = null;
//		}
//	}
//
//	/**
//	 * @see Entity::unstubQuery
//	 */
//	protected function unstubQuery() {
//		if( $this->query === null && array_key_exists( 'query', $this->data ) ) {
//			$deserializerFactory = new DeserializerFactory( WikibaseRepo::getDefaultInstance()->getDataValueFactory() );
//
//			$this->query = $deserializerFactory->newQueryDeSerializer()->deserialize( $this->data['query'] );
//		}
//	}

}
