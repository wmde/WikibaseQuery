<?php

namespace Wikibase\Query;

use DataTypes\DataTypeFactory;
use DataValues\DataValue;
use Wikibase\Lib\PropertyDataTypeLookup;
use Wikibase\QueryEngine\PropertyDataValueTypeLookup;

/**
 * @since 0.1
 *
 * @file
 * @ingroup WikibaseQuery
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class PropertyDataValueTypeFinder implements PropertyDataValueTypeLookup {

	protected $propertyDtLookup;
	protected $dtFactory;

	public function __construct( PropertyDataTypeLookup $propertyDtLookup, DataTypeFactory $dtFactory ) {
		$this->propertyDtLookup = $propertyDtLookup;
		$this->dtFactory = $dtFactory;
	}

	/**
	 * @see PropertyDataValueTypeLookup::getDataValueTypeForProperty
	 *
	 * @param DataValue $propertyId
	 *
	 * @return string
	 */
	public function getDataValueTypeForProperty( DataValue $propertyId ) {
		// TODO: verify is EntityId

		$dataTypeId = $this->propertyDtLookup->getDataTypeIdForProperty( $propertyId );

		// TODO: catch OutOfBoundsException
		$dataType = $this->dtFactory->getType( $dataTypeId );

		return $dataType->getDataValueType();
	}

}
