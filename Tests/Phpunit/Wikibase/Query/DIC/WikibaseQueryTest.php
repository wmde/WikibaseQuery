<?php

namespace Tests\Phpunit\Wikibase\Query\DIC;

use Wikibase\Query\DIC\WikibaseQuery;

/**
 * @covers Wikibase\Query\DIC\WikibaseQuery
 *
 * @file
 * @ingroup WikibaseQuery
 * @group WikibaseQuery
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class WikibaseQueryTest extends \PHPUnit_Framework_TestCase {

	public function testGetByPropertyValueEntityFinder() {
		$dependencyManager = $this->getMock( 'Wikibase\Query\DIC\DependencyManager' );

		$expectedObject = (object)array( 'awesomeness' => 9001 );

		$dependencyManager->expects( $this->once() )
			->method( 'newObject' )
			->with( $this->equalTo( 'byPropertyValueEntityFinder' ) )
			->will( $this->returnValue( $expectedObject ) );

		$registry = new WikibaseQuery( $dependencyManager );

		$entityFinder = $registry->getByPropertyValueEntityFinder();

		$this->assertEquals( $expectedObject, $entityFinder );
	}

}
