<?php

namespace Wikibase\Query\DIC\Builders;

use Symfony\Component\Console\Application;
use Wikibase\Query\DIC\DependencyBuilder;
use Wikibase\Query\DIC\DependencyManager;
use Wikibase\QueryEngine\Console\DumpSqlCommand;

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
	}

	private function newDumpCommand() {
		$command = new DumpSqlCommand();
		$command->setDependencies(
			$this->dependencyManager->newObject( 'sqlStoreSchema' ),
			$this->dependencyManager->newObject( 'connection' )->getDatabasePlatform()
		);
		return $command;
	}

}
