<?php

namespace Tests\Unit\Wikibase\Query;

use Ask\Language\Description\AnyValue;
use Ask\Language\Option\QueryOptions;
use Ask\Language\Query;
use Wikibase\Claim;
use Wikibase\EntityId;
use Wikibase\Query\QueryEntity;
use Wikibase\Query\QueryEntityDeserializer;
use Wikibase\Query\QueryId;

/**
 * @covers Wikibase\Query\QueryEntityDeserializer
 *
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
		return new QueryEntityDeserializer( $this->getMock( 'Deserializers\DispatchableDeserializer' ) );
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

		$canDeserialize = $deserializer->isDeserializerFor( $notAQueryEntitySerialization );

		$this->assertFalse( $canDeserialize );

	}

	public function testCallsQueryDeserializer(){
		$queryDeserializer = $this->getMock( 'Deserializers\DispatchableDeserializer' );

		$queryEntitySerialzation = $this->newQueryEntitySerialization();
		$mockQuery = $this->newQuery();

		$queryDeserializer->expects( $this->once() )
			->method( 'deserialize' )
			->with( $this->equalTo( $queryEntitySerialzation['query'] ) )
			->will( $this->returnValue( $mockQuery ) );

		$queryDeserializer->expects( $this->once() )
			->method( 'isDeserializerFor' )
			->with( $this->equalTo( $queryEntitySerialzation['query'] ) )
			->will( $this->returnValue( true ) );

		$deserializer = new QueryEntityDeserializer( $queryDeserializer );

		$deserialization = $deserializer->deserialize( $queryEntitySerialzation );

		$this->assertInstanceOf(
			'Wikibase\Query\QueryEntity',
			$deserialization
		);
	}

	public function newQueryEntitySerialization() {
		return array(
			'entity' => 'Y1337',

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
					'g' => 'foo',
				),
				array(
					'm' => array( 'value', 123, 'string', 'baz' ),
					'q' => array(
						array( 'somevalue', 42 ),
						array( 'somevalue', 43 ),
					),
					'g' => 'baz',
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
		$queryDeserializer = $this->getMock( 'Deserializers\DispatchableDeserializer' );

		$deserializer = new QueryEntityDeserializer( $queryDeserializer );

		$queryDeserializer->expects( $this->any() )
			->method( 'deserialize' )
			->will( $this->returnValue( $this->newQuery() ) );

		$queryDeserializer->expects( $this->once() )
			->method( 'isDeserializerFor' )
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

		$expectedId = new QueryId( $queryEntitySerialzation['entity'] );

		$this->assertEquals(
			$expectedId,
			$deserialization->getId()
		);
	}

	/**
	 * @dataProvider invalidIdProvider
	 */
	public function testCannotDeserializeWithInvalidId( $invalidIdSerialization ) {
		$serialization = $this->newQueryEntitySerialization();
		$serialization['entity'] = $invalidIdSerialization;

		$this->setExpectedException( 'Deserializers\Exceptions\DeserializationException' );
		$this->newQueryEntityDeserializer()->deserialize( $serialization );
	}

	public function invalidIdProvider() {
		$argLists = array();

		$argLists[] = array(
			'foo'
		);

		$argLists[] = array(
			'Q42'
		);

		$argLists[] = array(
			'Y 1'
		);

		$argLists[] = array(
			'Y1.42'
		);

		$argLists[] = array(
			array()
		);

		$argLists[] = array(
			array( 'item' )
		);

		$argLists[] = array(
			array( 'item', '42' )
		);

		$argLists[] = array(
			array( 42, 42 )
		);

		$argLists[] = array(
			array( 'item', 42, 'foo' )
		);

		$argLists[] = array(
			array( array(), false )
		);

		return $argLists;
	}

	/**
	 * @dataProvider invalidLabelsProvider
	 */
	public function testCannotDeserializeWithInvalidLabels( $invalidLabelsSerialization ) {
		$serialization = $this->newQueryEntitySerialization();
		$serialization['label'] = $invalidLabelsSerialization;

		$this->setExpectedException( 'Deserializers\Exceptions\DeserializationException' );
		$this->newQueryEntityDeserializer()->deserialize( $serialization );
	}

	public function invalidLabelsProvider() {
		$argLists = array();

		$argLists[] = array(
			'foo'
		);

		$argLists[] = array(
			array( array() )
		);

		$argLists[] = array(
			array( 'de' => array( 'foo' ) )
		);

		$argLists[] = array(
			42 => 'foo'
		);

		$argLists[] = array(
			'en' => 'foo',
			'bar'
		);

		$argLists[] = array(
			'en' => 'foo',
			'de' => false
		);

		return $argLists;
	}

	/**
	 * @dataProvider invalidLabelsProvider
	 */
	public function testCannotDeserializeWithInvalidDescriptions( $invalidDescriptionsSerialization ) {
		$serialization = $this->newQueryEntitySerialization();
		$serialization['description'] = $invalidDescriptionsSerialization;

		$this->setExpectedException( 'Deserializers\Exceptions\DeserializationException' );
		$this->newQueryEntityDeserializer()->deserialize( $serialization );
	}

	/**
	 * @dataProvider invalidAliasesProvider
	 */
	public function testCannotDeserializeWithInvalidAliases( $invalidAliasesSerialization ) {
		$serialization = $this->newQueryEntitySerialization();
		$serialization['aliases'] = $invalidAliasesSerialization;

		$this->setExpectedException( 'Deserializers\Exceptions\DeserializationException' );
		$this->newQueryEntityDeserializer()->deserialize( $serialization );
	}

	public function invalidAliasesProvider() {
		$argLists = array();

		$argLists[] = array(
			'foo'
		);

		$argLists[] = array(
			array( array() )
		);

		$argLists[] = array(
			array( 'de' => 'foo' )
		);

		$argLists[] = array(
			array( 'foo' )
		);

		$argLists[] = array(
			'en' => array( 'foo' ),
			'bar'
		);

		$argLists[] = array(
			'en' => array( 'foo' ),
			'de' => 'bar'
		);

		return $argLists;
	}

	/**
	 * @dataProvider invalidClaimsProvider
	 */
	public function testCannotDeserializeWithInvalidClaims( $invalidClaimsSerialization ) {
		$serialization = $this->newQueryEntitySerialization();
		$serialization['claim'] = $invalidClaimsSerialization;

		$this->setExpectedException( 'Deserializers\Exceptions\DeserializationException' );
		$this->newQueryEntityDeserializer()->deserialize( $serialization );
	}

	public function invalidClaimsProvider() {
		$argLists = array();

		$argLists[] = array(
			'foo'
		);

		$argLists[] = array(
			null
		);

		$argLists[] = array(
			array(
				'foo'
			)
		);

		$argLists[] = array(
			array(
				array(
					'm' => array( 'somevalue', 42 ),
					'q' => array(),
					'g' => 'foo',
				),
				'foo'
			)
		);

//		$argLists[] = array(
//			array(
//				array(
//				),
//			)
//		);
//
//		$argLists[] = array(
//			array(
//				array(
//					'q' => array(),
//					'g' => null,
//				),
//			)
//		);

		return $argLists;
	}

}
