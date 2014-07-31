<?php

namespace Wikibase\Query\DIC\Builders;

use Doctrine\DBAL\Connection;
use Symfony\Component\Console\Application;
use Wikibase\Query\Cli\EntitiesImporter\ImportCommand;
use Wikibase\Query\Cli\EntitiesImporter\ImporterBuilder;
use Wikibase\Query\Cli\SQLStoreInstallCommand;
use Wikibase\Query\Cli\SQLStoreUninstallCommand;
use Wikibase\Query\DIC\DependencyBuilder;
use Wikibase\Query\DIC\DependencyManager;
use Wikibase\QueryEngine\Console\DumpSqlCommand;
use Wikibase\QueryEngine\SQLStore\SQLStore;
use Wikibase\Repo\WikibaseRepo;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class CliApplicationBuilder extends DependencyBuilder {

	/**
	 * @var DependencyManager
	 */
	private $dependencyManager;

	/**
	 * @var Application
	 */
	private $app;

	/**
	 * @var WikibaseRepo
	 */
	private $repo;

	public function __construct( WikibaseRepo $repo ) {
		$this->repo = $repo;
	}

	/**
	 * @see DependencyBuilder::buildObject
	 *
	 * @param DependencyManager $dependencyManager
	 *
	 * @return Application
	 */
	public function buildObject( DependencyManager $dependencyManager ) {
		$this->dependencyManager = $dependencyManager;

		$this->app = new Application();

		$this->setApplicationInfo();
		$this->registerCommands();

		return $this->app;
	}

	private function setApplicationInfo() {
		$this->app->setName( 'Wikibase Query CLI' );
		$this->app->setVersion( WIKIBASE_QUERY_VERSION );
	}

	private function registerCommands() {
		$this->app->add( $this->newDumpCommand() );
		$this->app->add( $this->newInstallCommand() );
		$this->app->add( $this->newUninstallCommand() );
		$this->app->add( $this->newImportCommand() );
	}

	private function newDumpCommand() {
		$command = new DumpSqlCommand();

		$command->setDependencies(
			$this->dependencyManager->newObject( 'sqlStoreSchema' ),
			$this->dependencyManager->newObject( 'connection' )->getDatabasePlatform()
		);
		return $command;
	}

	private function newInstallCommand() {
		/**
		 * @var SQLStore $queryStore
		 */
		$queryStore = $this->dependencyManager->newObject( 'sqlStore' );

		/**
		 * @var Connection $connection
		 */
		$connection = $this->dependencyManager->newObject( 'connection' );

		$installer = $queryStore->newInstaller( $connection->getSchemaManager() );

		$command = new SQLStoreInstallCommand();
		$command->setDependencies( $installer );

		return $command;
	}

	private function newUninstallCommand() {
		/**
		 * @var SQLStore $queryStore
		 */
		$queryStore = $this->dependencyManager->newObject( 'sqlStore' );

		/**
		 * @var Connection $connection
		 */
		$connection = $this->dependencyManager->newObject( 'connection' );

		$uninstaller = $queryStore->newUninstaller( $connection->getSchemaManager() );

		$command = new SQLStoreUninstallCommand();
		$command->setDependencies( $uninstaller );

		return $command;
	}

	private function newImportCommand() {
		$command = new ImportCommand();

		$command->setDependencies( new ImporterBuilder(
			$this->dependencyManager->newObject( 'queryStoreWriter' ),
			$this->repo->getStore()->newEntityPerPage(),
			$this->repo->getEntityLookup(),
			$this->repo->getEntityIdParser()
		) );

		return $command;
	}

}
