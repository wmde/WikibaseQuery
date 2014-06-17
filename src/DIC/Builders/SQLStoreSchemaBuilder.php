<?php

namespace Wikibase\Query\DIC\Builders;

use DatabaseSqlite;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Wikibase\DataModel\Entity\BasicEntityIdParser;
use Wikibase\Query\DIC\DependencyBuilder;
use Wikibase\Query\DIC\DependencyManager;
use Wikibase\QueryEngine\SQLStore\DataValueHandlersBuilder;
use Wikibase\QueryEngine\SQLStore\StoreSchema;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class SQLStoreSchemaBuilder extends DependencyBuilder {

	private $tablePrefix;

	/**
	 * @param string $tablePrefix Table prefix to be used for the store
	 */
	public function __construct( $tablePrefix ) {
		$this->tablePrefix = $tablePrefix;
	}

	/**
	 * @see DependencyBuilder::buildObject
	 *
	 * @param DependencyManager $dependencyManager
	 *
	 * @return StoreSchema
	 */
	public function buildObject( DependencyManager $dependencyManager ) {
		// TODO: provide an extension mechanism for the DV handlers
		$handlersBuilder = new DataValueHandlersBuilder();
		$handlers = $handlersBuilder->withSimpleHandlers()
			->withEntityIdHandler( $this->getEntityIdParser() )->getHandlers();

		return new StoreSchema( $this->tablePrefix, $handlers );
	}

	private function getEntityIdParser() {
		// TODO: get via DIC
		return new BasicEntityIdParser();
	}

}
