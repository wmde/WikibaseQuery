<?php

namespace Tests\System\Wikibase\Query\DIC;

use Doctrine\DBAL\Connection;
use Wikibase\Query\DIC\WikibaseQueryBuilder;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class DatabaseConnectionBuilderTest extends \PHPUnit_Framework_TestCase {

	public function testConnectionHasTables() {
		$builder = new WikibaseQueryBuilder( $GLOBALS );

		/**
		 * @var Connection $connection
		 */
		$connection = $builder->buildDependencyManager()->newObject( 'connection' );
		$this->assertInstanceOf( 'Doctrine\DBAL\Connection', $connection );

		$tables = $connection->getSchemaManager()->listTableNames();

		$this->assertHasTable( 'page', $tables );
		$this->assertHasTable( 'wbq_mainsnak_entityid', $tables );
	}

	private function assertHasTable( $expected, array $actualTables ) {
		$this->assertContains(
			$GLOBALS['wgDBprefix'] . $expected,
			$actualTables,
			var_export( $actualTables, true )
		);
	}

}
