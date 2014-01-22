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
use Wikibase\QueryEngine\SQLStore\SQLStore;
use Wikibase\QueryEngine\SQLStore\StoreConfig;
use Wikibase\Repo\WikibaseRepo;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class SQLStoreBuilder extends DependencyBuilder {

	protected $storeName;
	protected $tablePrefix;

	/**
	 * @var QueryInterface
	 */
	protected $queryInterface;

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
		$this->queryInterface = $dependencyManager->newObject( 'slaveQueryInterface' );

		return new SQLStore( $this->newStoreConfig() );
	}

	protected function newStoreConfig() {
		// TODO: provide an extension mechanism for the DV handlers
		$dvHandlers = new DataValueHandlers();

		$config = new StoreConfig(
			$this->storeName,
			$this->tablePrefix,
			$dvHandlers->getHandlers()
		);

		$dvtLookup = new PropertyDataValueTypeFinder(
			WikibaseRepo::getDefaultInstance()->getPropertyDataTypeLookup(),
			WikibaseRepo::getDefaultInstance()->getDataTypeFactory()
		);

		$config->setPropertyDataValueTypeLookup( $dvtLookup );

		return $config;
	}

}
