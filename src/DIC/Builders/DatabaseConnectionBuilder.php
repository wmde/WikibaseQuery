<?php

namespace Wikibase\Query\DIC\Builders;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Wikibase\Query\DIC\DependencyBuilder;
use Wikibase\Query\DIC\DependencyManager;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class DatabaseConnectionBuilder extends DependencyBuilder {

	private $config;

	/**
	 * @param array $config Contains the database config, such as wgDBtype
	 */
	public function __construct( array $config ) {
		$this->config = $config;
	}

	/**
	 * @see DependencyBuilder::buildObject
	 *
	 * @param DependencyManager $dependencyManager
	 *
	 * @return Connection
	 */
	public function buildObject( DependencyManager $dependencyManager ) {
		// TODO: load balancing
		return DriverManager::getConnection( $this->getConnectionParams() );
	}

	private function getConnectionParams() {
		switch ( $this->config['wgDBtype'] ) {
			case 'mysql':
				return $this->getMySQLParams();
			case 'sqlite':
				return $this->getSQLiteParams();
		}

		throw new \RuntimeException( 'Unsupported database type' );
	}

	private function getMySQLParams() {
		return array(
			'driver' => 'pdo_mysql',
			'user' => $GLOBALS['wgDBuser'],
			'password' => $GLOBALS['wgDBpassword'],
			'host' => $GLOBALS['wgDBserver'],
			'dbname' => $GLOBALS['wgDBname']
		);
	}

	private function getSQLiteParams() {
		return array(
			'driver' => 'pdo_sqlite',
			'user' => $GLOBALS['wgDBuser'],
			'password' => $GLOBALS['wgDBpassword'],
			'host' => $GLOBALS['wgDBserver'],
			'dbname' => $GLOBALS['wgDBname']
		);
	}

}
