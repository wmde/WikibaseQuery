<?php

namespace Wikibase\Query\DIC\Builders;

use Wikibase\Query\ByPropertyValueEntityFinder;
use Wikibase\Query\DIC\DependencyBuilder;
use Wikibase\Query\DIC\DependencyManager;
use Wikibase\Query\PropertyDataValueTypeFinder;
use Wikibase\QueryEngine\SQLStore\DataValueHandlers;
use Wikibase\QueryEngine\SQLStore\Store;
use Wikibase\QueryEngine\SQLStore\StoreConfig;
use Wikibase\Repo\WikibaseRepo;

/**
 * @since 1.0
 *
 * @file
 * @ingroup WikibaseQuery
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class QueryStoreBuilder extends DependencyBuilder {

	/**
	 * @see DependencyBuilder::buildObject
	 *
	 * @param DependencyManager $dependencyManager
	 *
	 * @return ByPropertyValueEntityFinder
	 */
	public function buildObject( DependencyManager $dependencyManager ) {
		// TODO: provide an extension mechanism for the DV handlers
		$dvHandlers = new DataValueHandlers();

		$config = new StoreConfig(
			'Wikibase Query store v0.1',
			'wbq_',
			$dvHandlers->getHandlers()
		);

		$dvtLookup = new PropertyDataValueTypeFinder(
			WikibaseRepo::getDefaultInstance()->getPropertyDataTypeLookup(),
			WikibaseRepo::getDefaultInstance()->getDataTypeFactory()
		);

		$config->setPropertyDataValueTypeLookup( $dvtLookup );

		return new Store(
			$config,
			$dependencyManager->newObject( 'slaveQueryInterface' )
		);
	}

}
