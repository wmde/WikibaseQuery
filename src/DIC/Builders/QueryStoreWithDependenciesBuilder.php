<?php

namespace Wikibase\Query\DIC\Builders;

use Wikibase\Query\ByPropertyValueEntityFinder;
use Wikibase\Query\DIC\DependencyBuilder;
use Wikibase\Query\DIC\DependencyManager;
use Wikibase\Query\PropertyDataValueTypeFinder;
use Wikibase\QueryEngine\SQLStore\SQLStoreWithDependencies;
use Wikibase\Repo\WikibaseRepo;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class QueryStoreWithDependenciesBuilder extends DependencyBuilder {

	private $repo;

	public function __construct( WikibaseRepo $repo ) {
		$this->repo = $repo;
	}

	/**
	 * @see DependencyBuilder::buildObject
	 *
	 * @param DependencyManager $dependencyManager
	 *
	 * @return ByPropertyValueEntityFinder
	 */
	public function buildObject( DependencyManager $dependencyManager ) {
		return new SQLStoreWithDependencies(
			$dependencyManager->newObject( 'sqlStore' ),
			$dependencyManager->newObject( 'connection' ),
			$this->getDataValueTypeLookup(),
			$this->repo->getEntityIdParser()
		);
	}

	private function getDataValueTypeLookup() {
		return  new PropertyDataValueTypeFinder(
			$this->repo->getPropertyDataTypeLookup(),
			$this->repo->getDataTypeFactory()
		);
	}


}
