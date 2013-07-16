<?php

namespace Wikibase\Query\DIC\Builders;

use Wikibase\Database\LazyDBConnectionProvider;
use Wikibase\Database\MediaWikiQueryInterface;
use Wikibase\Database\MWDB\ExtendedMySQLAbstraction;
use Wikibase\Database\MWDB\ExtendedSQLiteAbstraction;
use Wikibase\Query\ByPropertyValueEntityFinder;
use Wikibase\Query\DIC\DependencyBuilder;
use Wikibase\Query\DIC\DependencyManager;

/**
 * @since 1.0
 *
 * @file
 * @ingroup WikibaseQuery
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class QueryInterfaceBuilder extends DependencyBuilder {

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
		$connectionProvider = $this->newConnectionProvider();

		return new MediaWikiQueryInterface(
			$connectionProvider,
			$this->newExtendedAbstraction( $connectionProvider )
		);
	}

	protected function newConnectionProvider() {
		return new LazyDBConnectionProvider( $this->connectionId );
	}

	// TODO: there should be a factory for this in the Wikibase Database component
	private function newExtendedAbstraction( $connectionProvider ) {
		if ( $this->dbType === 'mysql' ) {
			return new ExtendedMySQLAbstraction( $connectionProvider );
		}

		if ( $this->dbType === 'sqlite' ) {
			return new ExtendedSQLiteAbstraction( $connectionProvider );
		}

		throw new \Exception( 'Support for this dbType not implemented' );
	}

}
