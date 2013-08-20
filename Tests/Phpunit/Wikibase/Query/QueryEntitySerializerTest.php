<?php

namespace Tests\Phpunit\Wikibase\Query;

use Ask\Language\Description\AnyValue;
use Ask\Language\Option\QueryOptions;
use Ask\Language\Query;
use Wikibase\Claim;
use Wikibase\Claims;
use Wikibase\PropertyNoValueSnak;
use Wikibase\PropertySomeValueSnak;
use Wikibase\Query\QueryEntity;
use Wikibase\Query\QueryEntitySerializer;
use Wikibase\Test\ClaimListAccessTest;

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
		$this->newSimpleQueryEntitySerializer();
		$this->assertFalse( false );
	}

	protected function newSimpleQueryEntitySerializer() {
		return new QueryEntitySerializer( $this->getMock( 'Serializers\Serializer' ) );
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
	public function testAttemptToSerializeANonQueryEntityCausesException( $notAQueryEntity ) {
		$serializer = $this->newSimpleQueryEntitySerializer();

		$this->setExpectedException( 'InvalidArgumentException' );
		$serializer->serialize( $notAQueryEntity );
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
	public function testCannotSerialize( $notAQueryEntity ) {
		$serializer = $this->newSimpleQueryEntitySerializer();

		$this->assertFalse( $serializer->canSerialize( $notAQueryEntity ) );
	}

	public function testCanSerialize() {
		$queryEntity = $this->newSimpleEntity();
		$serializer = $this->newSimpleQueryEntitySerializer();
		$this->assertTrue( $serializer->canSerialize( $queryEntity ) );
	}

	public function testSerializationCallsQuerySerialization() {
		$querySerializer = $this->getMock( 'Serializers\Serializer' );

		$queryEntity = $this->newSimpleEntity();
		$mockSerialization = 'query serialization';

		$querySerializer->expects( $this->once() )
			->method( 'serialize' )
			->with( $this->equalTo( $queryEntity->getQuery() ) )
			->will( $this->returnValue( $mockSerialization ) );

		$serializer = new QueryEntitySerializer( $querySerializer );

		$serialization = $serializer->serialize( $queryEntity );

		$this->assertInternalType( 'array', $serialization );
		$this->assertArrayHasKey( 'query', $serialization );
		$this->assertEquals( $mockSerialization, $serialization['query'] );
	}

	public function testSerializationOfNotSetId() {
		$queryEntity = $this->newSimpleEntity();

		$serialization = $this->newSimpleQueryEntitySerializer()->serialize( $queryEntity );

		$this->assertHasSerializedId( $serialization, null );
	}

	/**
	 * @dataProvider idNumberProvider
	 */
	public function testSerializationContainsId( $idNumber ) {
		$queryEntity = $this->newSimpleEntity();

		$queryEntity->setId( $idNumber );

		$serialization = $this->newSimpleQueryEntitySerializer()->serialize( $queryEntity );

		$this->assertHasSerializedId( $serialization, array( $queryEntity->getType(), $idNumber ) );
	}

	public function idNumberProvider() {
		return array(
			array( 42 ),
			array( 9001 ),
			array( 31337 ),
		);
	}

	protected function assertHasSerializedId( $serialization, $expectedId ) {
		$this->assertInternalType( 'array', $serialization );
		$this->assertArrayHasKey( 'entity', $serialization );
		$this->assertEquals( $expectedId, $serialization['entity'] );
	}

	/**
	 * @dataProvider descriptionListProvider
	 */
	public function testSerializationContainsDescriptions( array $descriptionList ) {
		$queryEntity = $this->newSimpleEntity();

		$queryEntity->setDescriptions( $descriptionList );

		$serialization = $this->newSimpleQueryEntitySerializer()->serialize( $queryEntity );

		$this->assertHasSerializedDescriptions( $serialization, $descriptionList );
	}

	public function descriptionListProvider() {
		return array(
			array( array() ),

			array( array(
				'en' => 'Test Description'
			) ),

			array( array(
				'en' => 'Test Description',
				'de' => 'Die Teste Descript'
			) ),
		);
	}

	protected function assertHasSerializedDescriptions( $serialization, array $expectedDescriptions ) {
		$this->assertInternalType( 'array', $serialization );
		$this->assertArrayHasKey( 'description', $serialization );
		$this->assertEquals( $expectedDescriptions, $serialization['description'] );
	}

	/**
	 * @dataProvider labelListProvider
	 */
	public function testSerializationContainsLabels( array $labelList ) {
		$queryEntity = $this->newSimpleEntity();

		$queryEntity->setLabels( $labelList );

		$serialization = $this->newSimpleQueryEntitySerializer()->serialize( $queryEntity );

		$this->assertHasSerializedLabels( $serialization, $labelList );
	}

	public function labelListProvider() {
		return array(
			array( array() ),

			array( array(
				'en' => 'Test Label'
			) ),

			array( array(
				'en' => 'Test Label',
				'de' => 'Die Teste Descript'
			) ),
		);
	}

	protected function assertHasSerializedLabels( $serialization, array $expectedLabels ) {
		$this->assertInternalType( 'array', $serialization );
		$this->assertArrayHasKey( 'label', $serialization );
		$this->assertEquals( $expectedLabels, $serialization['label'] );
	}

	/**
	 * @dataProvider aliasListProvider
	 */
	public function testSerializationContainsAliases( array $aliasLists ) {
		$queryEntity = $this->newSimpleEntity();

		$queryEntity->setAllAliases( $aliasLists );

		$serialization = $this->newSimpleQueryEntitySerializer()->serialize( $queryEntity );

		$this->assertHasSerializedAliases( $serialization, $aliasLists );
	}

	public function aliasListProvider() {
		return array(
			array( array() ),

			array( array(
				'en' => array( 'foo' ),
			) ),

			array( array(
				'en' => array( 'foo', 'bar' ),
			) ),

			array( array(
				'en' => array( 'foo', 'bar' ),
				'de' => array( 'die', 'bar' ),
			) ),
		);
	}

	protected function assertHasSerializedAliases( $serialization, array $expectedLabels ) {
		$this->assertInternalType( 'array', $serialization );
		$this->assertArrayHasKey( 'aliases', $serialization );
		$this->assertEquals( $expectedLabels, $serialization['aliases'] );
	}

	/**
	 * @dataProvider claimListProvider
	 */
	public function testSerializationContainsClaims( array $claimList ) {
		$queryEntity = $this->newSimpleEntity();

		$queryEntity->setClaims( new Claims( $claimList ) );

		$serialization = $this->newSimpleQueryEntitySerializer()->serialize( $queryEntity );

		$this->assertHasSerializedClaims( $serialization, $claimList );
	}

	public function claimListProvider() {
		return array(
			array( array() ),

			array( array(
				new Claim( new PropertySomeValueSnak( 42 ) )
			) ),

			array( array(
				new Claim( new PropertySomeValueSnak( 42 ) ),
				new Claim( new PropertyNoValueSnak( 1337 ) )
			) ),
		);
	}

	/**
	 * @param string $serialization
	 * @param Claim[] $expectedClaims
	 */
	protected function assertHasSerializedClaims( $serialization, array $expectedClaims ) {
		$expectedSerialization = array();

		foreach ( $expectedClaims as $claim ) {
			$expectedSerialization[] = $claim->toArray();
		}

		$this->assertInternalType( 'array', $serialization );
		$this->assertArrayHasKey( 'claim', $serialization );
		$this->assertEquals( $expectedSerialization, $serialization['claim'] );
	}

}
