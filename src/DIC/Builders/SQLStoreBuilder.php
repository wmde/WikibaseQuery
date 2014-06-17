<?php

namespace Wikibase\Query\DIC\Builders;

use Wikibase\Query\ByPropertyValueEntityFinder;
use Wikibase\Query\DIC\DependencyBuilder;
use Wikibase\Query\DIC\DependencyManager;
use Wikibase\QueryEngine\SQLStore\SQLStore;
use Wikibase\QueryEngine\SQLStore\StoreConfig;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class SQLStoreBuilder extends DependencyBuilder {

	private $storeName;

	/**
	 * @param string $storeName Human readable name for the store
	 */
	public function __construct( $storeName ) {
		$this->storeName = $storeName;
	}

	/**
	 * @see DependencyBuilder::buildObject
	 *
	 * @param DependencyManager $dependencyManager
	 *
	 * @return ByPropertyValueEntityFinder
	 */
	public function buildObject( DependencyManager $dependencyManager ) {
		return new SQLStore(
			$dependencyManager->newObject( 'sqlStoreSchema' ),
			$this->newStoreConfig()
		);
	}

	private function newStoreConfig() {
		return new StoreConfig( $this->storeName );
	}

}
