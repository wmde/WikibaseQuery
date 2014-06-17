<?php

namespace Wikibase\Query\DIC\Builders;

use DatabaseSqlite;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Wikibase\DataModel\Entity\BasicEntityIdParser;
use Wikibase\Query\Console\CliApplicationFactory;
use Wikibase\Query\DIC\DependencyBuilder;
use Wikibase\Query\DIC\DependencyManager;
use Wikibase\QueryEngine\SQLStore\DataValueHandlersBuilder;
use Wikibase\QueryEngine\SQLStore\StoreSchema;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class CliApplicationFactoryBuilder extends DependencyBuilder {

	/**
	 * @see DependencyBuilder::buildObject
	 *
	 * @param DependencyManager $dependencyManager
	 *
	 * @return Connection
	 */
	public function buildObject( DependencyManager $dependencyManager ) {
		return new CliApplicationFactory( $dependencyManager->newObject( 'sqlStoreSchema' ) );
	}

}
