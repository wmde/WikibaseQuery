<?php

namespace Wikibase\Query\DIC\Builders;

use Wikibase\DataModel\Entity\BasicEntityIdParser;
use Wikibase\Query\ByPropertyValueEntityFinder;
use Wikibase\Query\DIC\DependencyBuilder;
use Wikibase\Query\DIC\DependencyManager;
use Wikibase\QueryEngine\SQLStore\DataValueHandlers;
use Wikibase\QueryEngine\SQLStore\DataValueHandlersBuilder;
use Wikibase\QueryEngine\SQLStore\SQLStore;
use Wikibase\QueryEngine\SQLStore\StoreConfig;
use Wikibase\QueryEngine\SQLStore\StoreSchema;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class SQLStoreBuilder extends DependencyBuilder {

	private $storeName;
	private $tablePrefix;

	/**
	 * @param string $storeName Human readable name for the store
	 * @param string $tablePrefix Table prefix to be used for the store
	 */
	public function __construct( $storeName, $tablePrefix ) {
		$this->storeName = $storeName;
		$this->tablePrefix = $tablePrefix;
	}

	/**
	 * @see DependencyBuilder::buildObject
	 *
	 * @param DependencyManager $dependencyManager
	 *
	 * @return ByPropertyValueEntityFinder
	 */
	public function buildObject( DependencyManager $dependencyManager ) {
		return new SQLStore( $this->newStoreSchema(), $this->newStoreConfig() );
	}

	private function newStoreSchema() {
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

	private function newStoreConfig() {
		return new StoreConfig( $this->storeName );
	}

}
