<?php

namespace Tests\Phpunit\Wikibase\Query;

use Ask\Language\Description\AnyValue;
use Ask\Language\Option\QueryOptions;
use Ask\Language\Query;
use Wikibase\Claim;
use Wikibase\EntityId;
use Wikibase\Query\QueryEntity;
use Wikibase\Query\QueryEntityDeserializer;

/**
 * @covers Wikibase\Query\QueryEntityDeserializer
 *
 * @file
 * @ingroup WikibaseQuery
 * @group WikibaseQuery
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 * @author Adam Shorland < adamshorland@gmail.com >
 */
class QueryEntityDeserializerTest extends \PHPUnit_Framework_TestCase {

	public function testCanConstruct() {
		$this->newSimpleQueryEntityDeserializer();
		$this->assertFalse( false );
	}

	protected function newSimpleQueryEntityDeserializer() {
		return new QueryEntityDeserializer( $this->getMock( 'Deserializers\Deserializer' ) );
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

	/**
	 * @dataProvider notAQueryEntityProvider
	 */
	public function testAttemptToDeserializeANonQueryEntityCausesException( $notAQueryEntitySerialization ) {
		$deserializer = $this->newSimpleQueryEntityDeserializer();

		$this->setExpectedException( 'Deserializers\Exceptions\DeserializationException' );
		$deserializer->deserialize( $notAQueryEntitySerialization );
	}

	public function notAQueryEntityProvider() {
		$argLists = array();

		$argLists[] = array( null );
		$argLists[] = array( true );
		$argLists[] = array( 42 );
		$argLists[] = array( 'foo bar baz' );
		$argLists[] = array( array( 1, 2, '3' ) );
		$argLists[] = array( (object)array( 1, 2, '3' ) );

		return $argLists;
	}

	/**
	 * @dataProvider notAQueryEntityProvider
	 */
	public function testCanNotDeserializeInvalidSerialization( $notAQueryEntitySerialization ){
		$deserializer = $this->newSimpleQueryEntityDeserializer();

		$canDeserialize = $deserializer->canDeserialize( $notAQueryEntitySerialization );

		$this->assertFalse( $canDeserialize );

	}

	public function testCallsQueryDeserializer(){
		$queryDeserializer = $this->getMock( 'Deserializers\Deserializer' );

		$queryEntitySerialzation = $this->newQueryEntitySerialization();
		$mockQuery = $this->newQuery();

		$queryDeserializer->expects( $this->once() )
			->method( 'deserialize' )
			->with( $this->equalTo( $queryEntitySerialzation['query'] ) )
			->will( $this->returnValue( $mockQuery ) );

		$queryDeserializer->expects( $this->once() )
			->method( 'canDeserialize' )
			->with( $this->equalTo( $queryEntitySerialzation['query'] ) )
			->will( $this->returnValue( true ) );

		$deserializer = new QueryEntityDeserializer( $queryDeserializer );

		$deserialization = $deserializer->deserialize( $queryEntitySerialzation );

		$this->assertInstanceOf(
			'Wikibase\Query\QueryEntity',
			$deserialization
		);
	}

	public function newQueryEntitySerialization(){
		return array(
			'entity' => array( 'query', 1337 ),

			'query' => array(
				'objectType' => 'query',
				'description' => array(
					'objectType' => 'description',
					'descriptionType' => 'anyValue',
					'value' => array(
						'property' => 'p1',
					)
				),
				'selectionRequests' => array(),
				'options' => array(
					'objectType' => 'queryOptions',
					'limit' => 10,
					'offset' => 0,
					'sort' => array(
						'expressions' => array()
					)
				),
			),

			'label' => array(
				'en' => 'Awesome',
				'de' => 'Awesome',
			),

			'description' => array(
				'en' => 'ohi',
				'de' => 'there',
			),

			'aliases' => array(
				'en' => array( 'foo', 'bar' ),
				'nl' => array( 'baz', 'hax' ),
			),

			'claim' => array(
				array(
					'm' => array( 'somevalue', 42 ),
					'q' => array(),
					'g' => null,
				),
				array(
					'm' => array( 'value', 123, 'string', 'baz' ),
					'q' => array(
						array( 'somevalue', 42 ),
						array( 'somevalue', 43 ),
					),
					'g' => null,
				)
			),
		);
	}

	public function testDeserializesLabelsDescriptionsAndAliases(){
		$deserializer = $this->newQueryEntityDeserializer();

		$queryEntitySerialzation = $this->newQueryEntitySerialization();

		$deserialization = $deserializer->deserialize( $queryEntitySerialzation );

		$this->assertInstanceOf(
			'Wikibase\Query\QueryEntity',
			$deserialization
		);

		$this->assertEquals(
			$queryEntitySerialzation['label'],
			$deserialization->getLabels()
		);

		$this->assertEquals(
			$queryEntitySerialzation['description'],
			$deserialization->getDescriptions()
		);

		$this->assertEquals(
			$queryEntitySerialzation['aliases'],
			$deserialization->getAllAliases()
		);
	}

	protected function newQueryEntityDeserializer() {
		$queryDeserializer = $this->getMock( 'Deserializers\Deserializer' );

		$deserializer = new QueryEntityDeserializer( $queryDeserializer );

		$queryDeserializer->expects( $this->any() )
			->method( 'deserialize' )
			->will( $this->returnValue( $this->newQuery() ) );

		$queryDeserializer->expects( $this->once() )
			->method( 'canDeserialize' )
			->will( $this->returnValue( true ) );

		return $deserializer;
	}

	public function testDeserializesClaims(){
		$deserializer = $this->newQueryEntityDeserializer();

		$queryEntitySerialzation = $this->newQueryEntitySerialization();

		$deserialization = $deserializer->deserialize( $queryEntitySerialzation );

		$this->assertInstanceOf(
			'Wikibase\Query\QueryEntity',
			$deserialization
		);

		$expectedClaims = array();

		foreach ( $queryEntitySerialzation['claim'] as $claimSerialization ) {
			$expectedClaims[] = Claim::newFromArray( $claimSerialization );
		}

		$this->assertEquals(
			$expectedClaims,
			$deserialization->getClaims()
		);
	}

	public function testDeserializesId(){
		$deserializer = $this->newQueryEntityDeserializer();

		$queryEntitySerialzation = $this->newQueryEntitySerialization();

		$deserialization = $deserializer->deserialize( $queryEntitySerialzation );

		$this->assertInstanceOf(
			'Wikibase\Query\QueryEntity',
			$deserialization
		);

		$idSerialization = $queryEntitySerialzation['entity'];
		$expectedId = new EntityId( $idSerialization[0], $idSerialization[1] );

		$this->assertEquals(
			$expectedId,
			$deserialization->getId()
		);
	}

}
