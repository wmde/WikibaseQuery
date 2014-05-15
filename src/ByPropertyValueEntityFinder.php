<?php

namespace Wikibase\Query;

use Ask\Language\Description\SomeProperty;
use Ask\Language\Description\ValueDescription;
use Ask\Language\Option\QueryOptions;
use DataValues\DataValue;
use DataValues\DataValueFactory;
use InvalidArgumentException;
use RuntimeException;
use Wikibase\DataModel\Entity\EntityId;
use Wikibase\DataModel\Entity\EntityIdParser;
use Wikibase\DataModel\Entity\EntityIdValue;
use Wikibase\DataModel\Entity\PropertyId;
use Wikibase\Lib\PropertyNotFoundException;
use Wikibase\QueryEngine\QueryEngine;

/**
 * @since 0.1
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class ByPropertyValueEntityFinder {

	protected $queryEngine;
	protected $dvFactory;
	protected $idParser;

	public function __construct( QueryEngine $queryEngine, DataValueFactory $dvFactory, EntityIdParser $idParser ) {
		$this->queryEngine = $queryEngine;
		$this->dvFactory = $dvFactory;
		$this->idParser = $idParser;
	}

	/**
	 * @param array $requestArguments
	 *
	 * @return EntityId[]
	 * @throws InvalidArgumentException
	 * @throws PropertyNotFoundException
	 */
	public function findEntities( array $requestArguments ) {
		// TODO: verify element existence
		$entityIds = $this->findEntitiesGivenRawArguments(
			$requestArguments['property'],
			$requestArguments['value'],
			$requestArguments['limit'],
			$requestArguments['offset']
		);
		return $entityIds;
	}

	/**
	 * @param string $propertyIdString
	 * @param string $valueString
	 * @param string $limit
	 * @param string $offset
	 *
	 * @return EntityId[]
	 * @throws InvalidArgumentException
	 * @throws PropertyNotFoundException
	 *
	 * TODO: Throw more specific exceptions.
	 */
	protected function findEntitiesGivenRawArguments( $propertyIdString, $valueString, $limit, $offset ) {
		$this->assertIsValidLimit( $limit );
		$this->assertIsValidOffset( $offset );

		$value = $this->getValueFromString( $valueString );
		$propertyId = $this->getPropertyIdFromString( $propertyIdString );

		return $this->findByPropertyValue( $propertyId, $value, $limit, $offset );
	}

	protected function assertIsValidLimit( $limit ) {
		if ( !ctype_digit( (string) $limit ) || (int)$limit < 1 ) {
			throw new InvalidArgumentException(
				'$limit needs to be a string representing a strictly positive integer, got: '
					. var_export( $limit, true )
			);
		}
	}

	protected function assertIsValidOffset( $offset ) {
		// ctype_digit does not accept the minus sign, so we also catch negative ints
		if ( !ctype_digit( (string) $offset ) ) {
			throw new InvalidArgumentException(
				'$offset needs to be a string representing a positive integer, got: '
					. var_export( $offset, true )
			);
		}
	}

	protected function getValueFromString( $valueString ) {
		$valueSerialization = $this->getValueSerialization( $valueString );

		try {
			return $this->dvFactory->newFromArray( $valueSerialization );
		}
		catch ( RuntimeException $ex ) {
			throw new InvalidArgumentException( $ex->getMessage(), 0, $ex );
		}
	}

	protected function getValueSerialization( $valueString ) {
		if ( !is_string( $valueString ) ) {
			throw new InvalidArgumentException( '$valueString needs to be a string serialization of a DataValue' );
		}

		$valueSerialization = json_decode( $valueString, true );

		if ( !is_array( $valueSerialization ) ) {
			throw new InvalidArgumentException( 'The provided value needs to be a serialization of a DataValue' );
		}

		return $valueSerialization;
	}

	protected function getPropertyIdFromString( $propertyIdString ) {
		try {
			$propertyId = $this->idParser->parse( $propertyIdString );
		}
		catch ( RuntimeException $ex ) {
			throw new InvalidArgumentException( $ex->getMessage(), 0, $ex );
		}

		if ( !( $propertyId instanceof PropertyId ) ) {
			throw new InvalidArgumentException( 'The provided EntityId needs to be a PropertyId' );
		}

		return $propertyId;
	}

	/**
	 * @param PropertyId $propertyId
	 * @param DataValue $value
	 * @param int $limit
	 * @param int $offset
	 *
	 * @return EntityId[]
	 */
	protected function findByPropertyValue( PropertyId $propertyId, DataValue $value, $limit, $offset ) {
		$description = new SomeProperty(
			new EntityIdValue( $propertyId ),
			new ValueDescription( $value )
		);

		$options = new QueryOptions( $limit, $offset );

		return $this->queryEngine->getMatchingEntities( $description, $options );
	}

}
