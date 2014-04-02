<?php

namespace Wikibase\Query;

use Deserializers\DispatchableDeserializer;
use Deserializers\Exceptions\DeserializationException;
use Deserializers\Exceptions\MissingAttributeException;
use InvalidArgumentException;
use Wikibase\Claim;

/**
 * Deserialization for the internal representation of QueryEntity objects.
 *
 * TODO: The term deserialization code can become a strategy so this class
 * can be used for external representation deserialization as well.
 *
 * @since 0.1
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 * @author Adam Shorland < adamshorland@gmail.com >
 */
class QueryEntityDeserializer implements DispatchableDeserializer {

	protected $queryDeserializer;

	protected $serialization;

	/**
	 * @var QueryEntity
	 */
	protected $queryEntity;

	public function __construct( DispatchableDeserializer $queryDeserializer ) {
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
		if( !$this->isDeserializerFor( $this->serialization ) ) {
			throw new DeserializationException( 'Cannot deserialize.' );
		}
	}

	protected function constructQueryEntity() {
		$query = $this->queryDeserializer->deserialize( $this->serialization['query'] );

		$this->queryEntity = new QueryEntity( $query );
	}

	protected function deserializeId() {
		try {
			$id = new QueryId( $this->serialization['entity'] );
		}
		catch ( InvalidArgumentException $ex ) {
			throw new DeserializationException( $ex->getMessage(), $ex );
		}

		$this->queryEntity->setId( $id );
	}

	protected function deserializeLabels() {
		$labels = $this->serialization['label'];

		if ( !is_array( $labels ) ) {
			throw new DeserializationException( 'Invalid labels provided' );
		}

		foreach ( $labels as $languageCode => $labelText ) {
			if ( !is_string( $languageCode ) || !is_string( $labelText ) ) {
				throw new DeserializationException( 'Invalid labels provided' );
			}

			$this->queryEntity->setLabel( $languageCode, $labelText );
		}
	}

	protected function deserializeDescriptions() {
		$descriptions = $this->serialization['description'];

		if ( !is_array( $descriptions ) ) {
			throw new DeserializationException( 'Invalid descriptions provided' );
		}

		foreach ( $this->serialization['description'] as $languageCode => $descriptionText ) {
			if ( !is_string( $languageCode ) || !is_string( $descriptionText ) ) {
				throw new DeserializationException( 'Invalid descriptions provided' );
			}

			$this->queryEntity->setDescription( $languageCode, $descriptionText );
		}
	}

	protected function deserializeAliases() {
		$aliasLists = $this->serialization['aliases'];

		if ( !is_array( $aliasLists ) ) {
			throw new DeserializationException( 'Invalid aliases provided' );
		}

		foreach ( $aliasLists as $languageCode => $aliases ) {
			if ( !is_string( $languageCode ) || !is_array( $aliases ) ) {
				// TODO: each alias should be a string
				throw new DeserializationException( 'Invalid aliases provided' );
			}

			$this->queryEntity->setAliases( $languageCode, $aliases );
		}
	}

	protected function deserializeClaims() {
		$claims = $this->serialization['claim'];

		if ( !is_array( $claims ) ) {
			throw new DeserializationException( 'Invalid claims provided' );
		}

		foreach ( $claims as $claimSerialization ) {
			if ( !is_array( $claimSerialization ) ) {
				throw new DeserializationException( 'Invalid claims provided' );
			}

			$claim = Claim::newFromArray( $claimSerialization );
			$this->queryEntity->addClaim( $claim );

			// TODO: try catch around Claim::newFromArray as soon as it actually throws exceptions
		}
	}

	public function isDeserializerFor( $serialization ) {
		if( !is_array( $serialization ) ) {
			return false;
		}

		foreach ( array( 'query', 'entity', 'label', 'description', 'aliases', 'claim' ) as $requiredKey ) {
			if ( !array_key_exists( $requiredKey, $serialization ) ) {
				return false;
			}
		}

		return $this->queryDeserializer->isDeserializerFor( $serialization['query'] );
	}

	protected function requireAttribute( $attributeName ) {
		if ( !array_key_exists( $attributeName, $this->serialization ) ) {
			throw new MissingAttributeException(
				$attributeName
			);
		}
	}

}
