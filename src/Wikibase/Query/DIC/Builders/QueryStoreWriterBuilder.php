<?php

namespace Wikibase\Query\DIC\Builders;

use Wikibase\Database\QueryInterface\QueryInterface;
use Wikibase\Query\DIC\DependencyBuilder;
use Wikibase\Query\DIC\DependencyManager;
use Wikibase\QueryEngine\QueryStoreWriter;
use Wikibase\QueryEngine\SQLStore\SQLStore;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class QueryStoreWriterBuilder extends DependencyBuilder {

	/**
	 * @see DependencyBuilder::buildObject
	 *
	 * @param DependencyManager $dependencyManager
	 *
	 * @return QueryStoreWriter
	 */
	public function buildObject( DependencyManager $dependencyManager ) {
		/**
		 * @var SQLStore $queryStore
		 */
		$queryStore = $dependencyManager->newObject( 'sqlStore' );

		/**
		 * @var QueryInterface $queryInterface
		 */
		$queryInterface = $dependencyManager->newObject( 'masterQueryInterface' );

		return $queryStore->newWriter( $queryInterface );
	}

}
