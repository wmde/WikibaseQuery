<?php

namespace Wikibase\Query\DIC\Builders;

use DataValues\DataValueFactory;
use Wikibase\Query\ByPropertyValueEntityFinder;
use Wikibase\Query\DIC\DependencyBuilder;
use Wikibase\Query\DIC\DependencyManager;
use Wikibase\QueryEngine\QueryStore;
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
		 * @var QueryStore $queryStore
		 */
		$queryStore = $dependencyManager->newObject( 'queryStore' );

		return new ByPropertyValueEntityFinder(
			$queryStore->getQueryEngine(),
			$this->repo->getDataValueFactory(),
			$this->repo->getEntityIdParser(),
			$this->repo->getEntityIdFormatter()
		);
	}

}
