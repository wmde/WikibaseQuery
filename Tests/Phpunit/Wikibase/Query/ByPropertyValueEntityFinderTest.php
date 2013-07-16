<?php

namespace Tests\Phpunit\Wikibase\Query;

use Ask\Language\Description\Description;
use Ask\Language\Description\SomeProperty;
use Ask\Language\Description\ValueDescription;
use Ask\Language\Option\QueryOptions;
use Ask\Language\Query;
use DataValues\DataValue;
use DataValues\StringValue;
use Wikibase\EntityId;
use Wikibase\Query\ByPropertyValueEntityFinder;

/**
 * @covers Wikibase\Query\ByPropertyValueEntityFinder
 *
 * @file
 * @ingroup WikibaseQuery
 * @group WikibaseQuery
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class ByPropertyValueEntityFinderTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @dataProvider queryProvider
	 */
	public function testCanConstruct( EntityId $propertyId, DataValue $dataValue, Description $description, QueryOptions $options ) {
		$queryEngine = $this->getMock( 'Wikibase\QueryEngine\QueryEngine' );

		$queryEngine->expects( $this->once() )
			->method( 'getMatchingEntities' )
			->with(
				$this->equalTo( $description ),
				$this->equalTo( $options )
			);

		$entityFinder = new ByPropertyValueEntityFinder( $queryEngine );
		$entities = $entityFinder->findEntities( $propertyId, $dataValue, $options->getLimit(), $options->getOffset() );

		$this->assertInternalType( 'array', $entities );
		$this->assertContainsOnlyInstancesOf( 'Wikibase\EntityId', $entities );
	}

	public function queryProvider() {
		$argLists = array();

		$p42 = new EntityId( 'property', 42 );
		$p9001 = new EntityId( 'property', 9001 );
		$fooString = new StringValue( 'foo' );
		$barString = new StringValue( 'bar baz' );

		$argLists[] = array(
			$p42,
			$fooString,
			new SomeProperty(
				$p42,
				new ValueDescription( $fooString )
			),
			new QueryOptions( 10, 0 )
		);

		$argLists[] = array(
			$p9001,
			$barString,
			new SomeProperty(
				$p9001,
				new ValueDescription( $barString )
			),
			new QueryOptions( 42, 100 )
		);

		return $argLists;
	}

}
