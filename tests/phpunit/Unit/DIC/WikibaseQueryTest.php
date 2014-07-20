<?php

namespace Tests\Unit\Wikibase\Query\DIC;

use Wikibase\Query\DIC\WikibaseQuery;

/**
 * @covers Wikibase\Query\DIC\WikibaseQuery
 *
 * @group WikibaseQuery
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class WikibaseQueryTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @dataProvider objectKeyAndMethodProvider
	 */
	public function testCanGetObjects( $objectKey, $registryMethodName ) {
		$dependencyManager = $this->getMock( 'Wikibase\Query\DIC\DependencyManager' );

		$expectedObject = (object)array( 'awesomeness' => 9001 );

		$dependencyManager->expects( $this->once() )
			->method( 'newObject' )
			->with( $this->equalTo( $objectKey ) )
			->will( $this->returnValue( $expectedObject ) );

		$registry = new WikibaseQuery( $dependencyManager );

		$actualObject = call_user_func( array( $registry, $registryMethodName ) );

		$this->assertEquals( $expectedObject, $actualObject );
	}

	public function objectKeyAndMethodProvider() {
		$argLists = array();

		$argLists[] = array( 'byPropertyValueEntityFinder', 'getByPropertyValueEntityFinder' );
		$argLists[] = array( 'queryStoreWithDependencies', 'getQueryStoreWithDependencies' );
		$argLists[] = array( 'extensionUpdater', 'getExtensionUpdater' );
		$argLists[] = array( 'queryStoreWriter', 'getQueryStoreWriter' );

		return $argLists;
	}

}
