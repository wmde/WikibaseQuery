<?php

namespace Tests\Phpunit\Wikibase\Query;

use Ask\Language\Description\AnyValue;
use Ask\Language\Option\QueryOptions;
use Ask\Language\Query;
use Wikibase\Query\QueryEntity;
use Wikibase\Query\QueryEntitySerializer;

/**
 * @covers Wikibase\Query\QueryEntitySerializer
 *
 * @file
 * @ingroup WikibaseQuery
 * @group WikibaseQuery
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 * @author Adam Shorland < adamshorland@gmail.com >
 */
class QueryEntitySerializerTest extends \PHPUnit_Framework_TestCase {

	public function testCanConstruct() {
		new QueryEntitySerializer( $this->getMock( 'Serializers\Serializer' ) );
		$this->assertFalse( false );
	}

	public function testSerializationCallsQuerySerialization() {
		$querySerializer = $this->getMock( 'Serializers\Serializer' );

		$queryEntity = $this->newSimpleEntity();
		$mockSerialization = 'query serialization';

		$querySerializer->expects( $this->once() )
			->method( 'serialize' )
			->with( $this->equalTo( $queryEntity ) )
			->will( $this->returnValue( $mockSerialization ) );

		$serializer = new QueryEntitySerializer( $querySerializer );

		$serialization = $serializer->serialize( $queryEntity );

		$this->assertArrayHasKey( 'query', $serialization );
		$this->assertEquals( $mockSerialization, $serialization['query'] );
	}

	protected function newSimpleEntity() {
		return new QueryEntity( $this->newQuery() );
	}

	protected function newQuery() {
		return new Query(
			new AnyValue(),
			array(),
			new QueryOptions( 1, 0 )
		);
	}

}
