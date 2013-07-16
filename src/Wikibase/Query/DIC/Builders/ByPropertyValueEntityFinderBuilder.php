<?php

namespace Wikibase\Query\DIC\Builders;

use Wikibase\Query\ByPropertyValueEntityFinder;
use Wikibase\Query\DIC\DependencyBuilder;
use Wikibase\Query\DIC\DependencyManager;
use Wikibase\QueryEngine\QueryStore;

/**
 * @since 1.0
 *
 * @file
 * @ingroup WikibaseQuery
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
		return new ByPropertyValueEntityFinder( $queryStore->getQueryEngine() );
	}

}