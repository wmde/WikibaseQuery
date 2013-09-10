<?php

namespace Wikibase\Query\DIC\Builders;

use Wikibase\Database\LazyDBConnectionProvider;
use Wikibase\Database\MediaWiki\MWTableBuilderBuilder;
use Wikibase\Query\ByPropertyValueEntityFinder;
use Wikibase\Query\DIC\DependencyBuilder;
use Wikibase\Query\DIC\DependencyManager;
use Wikibase\Query\PropertyDataValueTypeFinder;
use Wikibase\QueryEngine\SQLStore\DataValueHandlers;
use Wikibase\QueryEngine\SQLStore\Store;
use Wikibase\QueryEngine\SQLStore\StoreConfig;
use Wikibase\Repo\WikibaseRepo;

/**
 * @since 1.0
 *
 * @file
 * @ingroup WikibaseQuery
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class QueryStoreBuilder extends DependencyBuilder {

	protected $connectionId;
	protected $dbType;

	/**
	 * @param int $connectionId ie DB_MASTER, DB_SLAVE
	 * @param string $dbType ie mysql, sqlite
	 */
	public function __construct( $connectionId, $dbType ) {
		$this->connectionId = $connectionId;
		$this->dbType = $dbType;
	}

	/**
	 * @see DependencyBuilder::buildObject
	 *
	 * @param DependencyManager $dependencyManager
	 *
	 * @return ByPropertyValueEntityFinder
	 */
	public function buildObject( DependencyManager $dependencyManager ) {
		// TODO: provide an extension mechanism for the DV handlers
		$dvHandlers = new DataValueHandlers();

		$config = new StoreConfig(
			'Wikibase Query store v0.1',
			'wbq_',
			$dvHandlers->getHandlers()
		);

		$dvtLookup = new PropertyDataValueTypeFinder(
			WikibaseRepo::getDefaultInstance()->getPropertyDataTypeLookup(),
			WikibaseRepo::getDefaultInstance()->getDataTypeFactory()
		);

		$config->setPropertyDataValueTypeLookup( $dvtLookup );

		$tbBuilder = new MWTableBuilderBuilder();
		$tbBuilder->setConnection( $this->newConnectionProvider() );

		return new Store(
			$config,
			$dependencyManager->newObject( 'slaveQueryInterface' ),
			$tbBuilder->getTableBuilder()
		);
	}

	protected function newConnectionProvider() {
		return new LazyDBConnectionProvider( $this->connectionId );
	}

}
