<?php

namespace Wikibase\Query\DIC\Builders;

use Doctrine\DBAL\Connection;
use Wikibase\Query\ByPropertyValueEntityFinder;
use Wikibase\Query\DIC\DependencyBuilder;
use Wikibase\Query\DIC\DependencyManager;
use Wikibase\Query\PropertyDataValueTypeFinder;
use Wikibase\QueryEngine\SQLStore\SQLStore;
use Wikibase\Repo\WikibaseRepo;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class ByPropertyValueEntityFinderBuilder extends DependencyBuilder {

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
		return new ByPropertyValueEntityFinder(
			$this->newQueryEngine( $dependencyManager ),
			$this->repo->getDataValueFactory(),
			$this->repo->getEntityIdParser()
		);
	}

	private function newQueryEngine( DependencyManager $dependencyManager ) {
		/**
		 * @var SQLStore $queryStore
		 */
		$queryStore = $dependencyManager->newObject( 'sqlStore' );

		/**
		 * @var Connection $connection
		 */
		$connection = $dependencyManager->newObject( 'connection' );

		return $queryStore->newQueryEngine(
			$connection,
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
