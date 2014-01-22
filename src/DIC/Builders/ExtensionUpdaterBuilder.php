<?php

namespace Wikibase\Query\DIC\Builders;

use Wikibase\Database\DBConnectionProvider;
use Wikibase\Database\MediaWiki\MediaWikiSchemaModifierBuilder;
use Wikibase\Database\MediaWiki\MWTableBuilderBuilder;
use Wikibase\Database\MediaWiki\MWTableDefinitionReaderBuilder;
use Wikibase\Database\QueryInterface\QueryInterface;
use Wikibase\Query\DIC\DependencyBuilder;
use Wikibase\Query\DIC\DependencyManager;
use Wikibase\Query\Setup\ExtensionUpdater;
use Wikibase\QueryEngine\SQLStore\SQLStore;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class ExtensionUpdaterBuilder extends DependencyBuilder {

	/**
	 * @var SQLStore
	 */
	protected $queryStore;

	/**
	 * @var DBConnectionProvider
	 */
	protected $connectionProvider;

	/**
	 * @var QueryInterface
	 */
	protected $queryInterface;

	/**
	 * @see DependencyBuilder::buildObject
	 *
	 * @param DependencyManager $dependencyManager
	 *
	 * @return ExtensionUpdater
	 */
	public function buildObject( DependencyManager $dependencyManager ) {
		$this->queryStore = $dependencyManager->newObject( 'sqlStore' );
		$this->connectionProvider = $dependencyManager->newObject( 'masterConnectionProvider' );
		$this->queryInterface = $dependencyManager->newObject( 'masterQueryInterface' );

		return new ExtensionUpdater(
			$this->newQueryStoreInstaller(),
			$this->newQueryStoreUpdater()
		);
	}

	protected function newQueryStoreInstaller() {
		return $this->queryStore->newInstaller(
			$this->newTableBuilder()
		);
	}

	protected function newQueryStoreUpdater() {
		return $this->queryStore->newUpdater(
			$this->newTableBuilder(),
			$this->newTableDefinitionReader(),
			$this->newSchemaModifier()
		);
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
