<?php

namespace Wikibase\Query\Console;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Symfony\Component\Console\Application;
use Wikibase\QueryEngine\Console\DumpSqlCommand;
use Wikibase\QueryEngine\SQLStore\StoreSchema;

/**
 * Builds the Wikibase Query CLI application.
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class CliApplicationFactory {

	/**
	 * @var Application
	 */
	private $app;

	/**
	 * @var StoreSchema
	 */
	private $schema;

	/**
	 * @var AbstractPlatform
	 */
	private $platform;

	public function __construct( StoreSchema $schema, AbstractPlatform $platform ) {
		$this->schema = $schema;
		$this->platform = $platform;
	}

	/**
	 * @return Application
	 */
	public function newApplication() {
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
		$command->setDependencies( $this->schema, $this->platform );
		return $command;
	}

}