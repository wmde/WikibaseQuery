<?php

namespace Wikibase\Query\DIC\Builders;

use DataValues\DataValueFactory;
use Wikibase\Query\ByPropertyValueEntityFinder;
use Wikibase\Query\DIC\DependencyBuilder;
use Wikibase\Query\DIC\DependencyManager;
use Wikibase\QueryEngine\QueryStore;
use Wikibase\Repo\WikibaseRepo;

/**
 * @since 1.0
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class ByPropertyValueEntityFinderBuilder extends DependencyBuilder {

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
			DataValueFactory::singleton(), // TODO: get instance from repo factory
			WikibaseRepo::getDefaultInstance()->getEntityIdParser(),
			WikibaseRepo::getDefaultInstance()->getEntityIdFormatter()
		);
	}

}
