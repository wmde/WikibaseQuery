<?php

namespace Wikibase\Query\DIC\Builders;

use Wikibase\Database\LazyDBConnectionProvider;
use Wikibase\Database\MediaWiki\MediaWikiQueryInterface;
use Wikibase\Database\QueryInterface\QueryInterface;
use Wikibase\Query\DIC\DependencyBuilder;
use Wikibase\Query\DIC\DependencyManager;

/**
 * @since 1.0
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
	 * @return QueryInterface
	 */
	public function buildObject( DependencyManager $dependencyManager ) {
		return new MediaWikiQueryInterface( $this->newConnectionProvider() );
	}

	protected function newConnectionProvider() {
		return new LazyDBConnectionProvider( $this->connectionId );
	}

}
