<?php

namespace Wikibase\Query;

use Ask\Language\Description\SomeProperty;
use Ask\Language\Description\ValueDescription;
use Ask\Language\Option\QueryOptions;
use Ask\Language\Query;
use DataValues\DataValue;
use Wikibase\EntityId;
use Wikibase\QueryEngine\QueryEngine;
use Wikibase\QueryEngine\SQLStore\Engine\DescriptionMatchFinder;

/**
 * @since 0.1
 *
 * @file
 * @ingroup WikibaseQuery
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class ByPropertyValueEntityFinder {

	protected $queryEngine;

	public function __construct( QueryEngine $queryEngine ) {
		$this->queryEngine = $queryEngine;
	}

	/**
	 * @param EntityId $propertyId
	 * @param DataValue $value
	 * @param int $limit
	 * @param int $offset
	 *
	 * @return EntityId[]
	 */
	public function findEntities( EntityId $propertyId, DataValue $value, $limit, $offset ) {
		$description = new SomeProperty(
			$propertyId,
			new ValueDescription( $value )
		);

		$options = new QueryOptions( $limit, $offset );

		$this->queryEngine->getMatchingEntities( $description, $options );

		return array(); // TODO
	}

}
