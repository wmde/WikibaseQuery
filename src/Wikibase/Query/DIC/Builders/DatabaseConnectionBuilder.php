<?php

namespace Wikibase\Query\DIC\Builders;

use Wikibase\Database\DBConnectionProvider;
use Wikibase\Database\LazyDBConnectionProvider;
use Wikibase\Query\DIC\DependencyBuilder;
use Wikibase\Query\DIC\DependencyManager;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class DatabaseConnectionBuilder extends DependencyBuilder {

	protected $connectionId;

	/**
	 * @param int $connectionId ie DB_MASTER, DB_SLAVE
	 */
	public function __construct( $connectionId ) {
		$this->connectionId = $connectionId;
	}

	/**
	 * @see DependencyBuilder::buildObject
	 *
	 * @param DependencyManager $dependencyManager
	 *
	 * @return DBConnectionProvider
	 */
	public function buildObject( DependencyManager $dependencyManager ) {
		return new LazyDBConnectionProvider( $this->connectionId );
	}

}
