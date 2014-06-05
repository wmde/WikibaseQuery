<?php

namespace Wikibase\Query\DIC\Builders;

use Doctrine\DBAL\Connection;
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
		 * @var Connection $connection
		 */
		$connection = $dependencyManager->newObject( 'connection' );

		return $queryStore->newWriter( $connection );
	}

}
