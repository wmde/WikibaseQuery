<?php

namespace Tests\Unit\Wikibase\Query\Setup;

use Wikibase\Query\Setup\ExtensionUpdater;

/**
 * @covers Wikibase\Query\Setup\ExtensionUpdater
 *
 * @group Wikibase
 * @group WikibaseQuery
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class ExtensionUpdaterTest extends \PHPUnit_Framework_TestCase {

	public function testRunForNewInstall() {
		$queryStoreInstaller = $this->getMock( 'Wikibase\QueryEngine\QueryStoreInstaller' );
		$queryStoreUpdater = $this->getMock( 'Wikibase\QueryEngine\QueryStoreUpdater' );

		$queryStoreInstaller->expects( $this->once() )
			->method( 'install' );

		$queryStoreUpdater->expects( $this->once() )
			->method( 'update' );

		$dbUpdater = $this->getMockBuilder( 'MysqlUpdater' )
			->disableOriginalConstructor()->getMock();

		$dbUpdater->expects( $this->exactly( 1 ) )
			->method( 'updateRowExists' )
			->will( $this->returnValue( false ) );

		$dbUpdater->expects( $this->exactly( 1 ) )
			->method( 'insertUpdateRow' );

		$updater = new ExtensionUpdater( $queryStoreInstaller, $queryStoreUpdater );
		$updater->run( $dbUpdater );
	}

	public function testRunForExistingInstall() {
		$queryStoreInstaller = $this->getMock( 'Wikibase\QueryEngine\QueryStoreInstaller' );
		$queryStoreUpdater = $this->getMock( 'Wikibase\QueryEngine\QueryStoreUpdater' );

		$queryStoreInstaller->expects( $this->never() )
			->method( 'install' );

		$queryStoreUpdater->expects( $this->once() )
			->method( 'update' );

		$dbUpdater = $this->getMockBuilder( 'MysqlUpdater' )
			->disableOriginalConstructor()->getMock();

		$dbUpdater->expects( $this->exactly( 1 ) )
			->method( 'updateRowExists' )
			->will( $this->returnValue( true ) );

		$dbUpdater->expects( $this->never() )
			->method( 'insertUpdateRow' );

		$updater = new ExtensionUpdater( $queryStoreInstaller, $queryStoreUpdater );
		$updater->run( $dbUpdater );
	}

}
