<?php

namespace Wikibase\Query;

use Deserializers\Deserializer;
use Deserializers\Exceptions\DeserializationException;
use Wikibase\Claim;
use Wikibase\EntityId;

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
class QueryEntityDeserializer implements Deserializer {

	protected $queryDeserializer;

	protected $serialization;

	/**
	 * @var QueryEntity
	 */
	protected $queryEntity;

	public function __construct( Deserializer $queryDeserializer ) {
		$this->queryDeserializer = $queryDeserializer;
	}

	public function deserialize( $serialization ) {
		$this->serialization = $serialization;

		$this->assertCanDeserialize();
		$this->constructQueryEntity();

		$this->deserializeId();

		$this->deserializeLabels();
		$this->deserializeDescriptions();
		$this->deserializeAliases();

		$this->deserializeClaims();

		return $this->queryEntity;
	}

	protected function assertCanDeserialize() {
		if( !$this->canDeserialize( $this->serialization ) ) {
			throw new DeserializationException( 'Cannot deserialize.' );
		}
	}

	protected function constructQueryEntity() {
		$query = $this->queryDeserializer->deserialize( $this->serialization['query'] );

		$this->queryEntity = new QueryEntity( $query );
	}

	protected function deserializeId() {
		// TODO: verify key exists
		// TODO: value validation
		$idSerialization = $this->serialization['entity'];
		$this->queryEntity->setId( new EntityId( $idSerialization[0], $idSerialization[1] ) );
	}

	protected function deserializeLabels() {
		// TODO: verify key exists
		// TODO: value validation
		foreach ( $this->serialization['label'] as $languageCode => $labelText ) {
			$this->queryEntity->setLabel( $languageCode, $labelText );
		}
	}

	protected function deserializeDescriptions() {
		// TODO: verify key exists
		// TODO: value validation
		foreach ( $this->serialization['description'] as $languageCode => $descriptionText ) {
			$this->queryEntity->setDescription( $languageCode, $descriptionText );
		}
	}

	protected function deserializeAliases() {
		// TODO: verify key exists
		// TODO: value validation
		foreach ( $this->serialization['aliases'] as $languageCode => $aliases ) {
			$this->queryEntity->setAliases( $languageCode, $aliases );
		}
	}

	protected function deserializeClaims() {
		// TODO: verify key exists
		// TODO: value validation
		foreach ( $this->serialization['claim'] as $claimSerialization ) {
			$this->queryEntity->addClaim( Claim::newFromArray( $claimSerialization ) );
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
