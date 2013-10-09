<?php

namespace Wikibase\Query\DIC\Builders;

use Wikibase\Query\DIC\DependencyBuilder;
use Wikibase\Query\DIC\DependencyManager;
use Wikibase\Query\Setup\ExtensionUpdater;
use Wikibase\QueryEngine\QueryStore;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class ExtensionUpdaterBuilder extends DependencyBuilder {

	/**
	 * @see DependencyBuilder::buildObject
	 *
	 * @param DependencyManager $dependencyManager
	 *
	 * @return ExtensionUpdater
	 */
	public function buildObject( DependencyManager $dependencyManager ) {
		/**
		 * @var QueryStore $queryStore
		 */
		$queryStore = $dependencyManager->newObject( 'queryStore' );
		return new ExtensionUpdater( $queryStore->newSetup() );
	}

}
