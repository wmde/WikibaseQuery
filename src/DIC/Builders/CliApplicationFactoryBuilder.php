<?php

namespace Wikibase\Query\DIC\Builders;

use Doctrine\DBAL\Connection;
use Wikibase\Query\Console\CliApplicationFactory;
use Wikibase\Query\DIC\DependencyBuilder;
use Wikibase\Query\DIC\DependencyManager;

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
		return new CliApplicationFactory(
			$dependencyManager->newObject( 'sqlStoreSchema' ),
			$dependencyManager->newObject( 'connection' )->getDatabasePlatform()
		);
	}

}
