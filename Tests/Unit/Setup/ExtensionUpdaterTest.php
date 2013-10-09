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
		$queryStoreSetup = $this->getMock( 'Wikibase\QueryEngine\QueryStoreSetup' );

		$queryStoreSetup->expects( $this->once() )
			->method( 'update' );

		$queryStoreSetup->expects( $this->once() )
			->method( 'install' );

		$dbUpdater = $this->getMockBuilder( 'MysqlUpdater' )
			->disableOriginalConstructor()->getMock();

		$dbUpdater->expects( $this->exactly( 1 ) )
			->method( 'updateRowExists' )
			->will( $this->returnValue( false ) );

		$dbUpdater->expects( $this->exactly( 1 ) )
			->method( 'insertUpdateRow' );

		$updater = new ExtensionUpdater( $queryStoreSetup );
		$updater->run( $dbUpdater );
	}

	public function testRunForExistingInstall() {
		$queryStoreSetup = $this->getMock( 'Wikibase\QueryEngine\QueryStoreSetup' );

		$queryStoreSetup->expects( $this->once() )
			->method( 'update' );

		$queryStoreSetup->expects( $this->never() )
			->method( 'install' );

		$dbUpdater = $this->getMockBuilder( 'MysqlUpdater' )
			->disableOriginalConstructor()->getMock();

		$dbUpdater->expects( $this->exactly( 1 ) )
			->method( 'updateRowExists' )
			->will( $this->returnValue( true ) );

		$dbUpdater->expects( $this->never() )
			->method( 'insertUpdateRow' );

		$updater = new ExtensionUpdater( $queryStoreSetup );
		$updater->run( $dbUpdater );
	}

}
