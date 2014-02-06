<?php

namespace Wikibase\Query\DIC\Builders;

use Wikibase\Database\QueryInterface\QueryInterface;
use Wikibase\Query\ByPropertyValueEntityFinder;
use Wikibase\Query\DIC\DependencyBuilder;
use Wikibase\Query\DIC\DependencyManager;
use Wikibase\QueryEngine\SQLStore\SQLStore;
use Wikibase\Repo\WikibaseRepo;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class ByPropertyValueEntityFinderBuilder extends DependencyBuilder {

	protected $repo;

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
		/**
		 * @var SQLStore $queryStore
		 */
		$queryStore = $dependencyManager->newObject( 'sqlStore' );

		/**
		 * @var QueryInterface $queryInterface
		 */
		$queryInterface = $dependencyManager->newObject( 'slaveQueryInterface' );

		return new ByPropertyValueEntityFinder(
			$queryStore->newQueryEngine( $queryInterface ),
			$this->repo->getDataValueFactory(),
			$this->repo->getEntityIdParser()
		);
	}

}
