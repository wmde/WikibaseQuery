<?php

namespace Wikibase\Query\DIC\Builders;

use Wikibase\Database\DBConnectionProvider;
use Wikibase\Database\LazyDBConnectionProvider;
use Wikibase\Database\MediaWiki\MediaWikiSchemaModifierBuilder;
use Wikibase\Database\MediaWiki\MWTableBuilderBuilder;
use Wikibase\Database\MediaWiki\MWTableDefinitionReaderBuilder;
use Wikibase\Database\QueryInterface\QueryInterface;
use Wikibase\Query\ByPropertyValueEntityFinder;
use Wikibase\Query\DIC\DependencyBuilder;
use Wikibase\Query\DIC\DependencyManager;
use Wikibase\Query\PropertyDataValueTypeFinder;
use Wikibase\QueryEngine\SQLStore\DataValueHandlers;
use Wikibase\QueryEngine\SQLStore\Store;
use Wikibase\QueryEngine\SQLStore\StoreConfig;
use Wikibase\Repo\WikibaseRepo;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class QueryStoreBuilder extends DependencyBuilder {

	protected $connectionId;
	protected $dbType;

	/**
	 * @var DBConnectionProvider
	 */
	protected $connectionProvider;

	/**
	 * @var QueryInterface
	 */
	protected $queryInterface;

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
		$this->connectionProvider = $this->newConnectionProvider();
		$this->queryInterface = $dependencyManager->newObject( 'slaveQueryInterface' );

		return new Store(
			$this->newStoreConfig(),
			$this->queryInterface,
			$this->newTableBuilder(),
			$this->newTableDefinitionReader(),
			$this->newSchemaModifier()
		);
	}

	protected function newStoreConfig() {
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

		return $config;
	}

	protected function newConnectionProvider() {
		return new LazyDBConnectionProvider( $this->connectionId );
	}

	protected function newTableBuilder() {
		$tbBuilder = new MWTableBuilderBuilder();

		return $tbBuilder->setConnection( $this->connectionProvider )
			->getTableBuilder();
	}

	protected function newTableDefinitionReader() {
		$drBuilder = new MWTableDefinitionReaderBuilder();

		return $drBuilder->setConnection( $this->connectionProvider )
			->setQueryInterface( $this->queryInterface )
			->getTableDefinitionReader();
	}

	protected function newSchemaModifier() {
		$smBuilder = new MediaWikiSchemaModifierBuilder();

		return $smBuilder->setConnection( $this->connectionProvider )
			->setQueryInterface( $this->queryInterface )
			->getSchemaModifier();
	}

}
