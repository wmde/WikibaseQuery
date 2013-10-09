<?php

namespace Tests\Integration\Wikibase\Query;

use Ask\DeserializerFactory;
use Ask\Language\Description\Disjunction;
use Ask\Language\Description\SomeProperty;
use Ask\Language\Description\ValueDescription;
use Ask\Language\Option\QueryOptions;
use Ask\Language\Query;
use Ask\Language\Selection\PropertySelection;
use Ask\SerializerFactory;
use DataValues\StringValue;
use Wikibase\Claim;
use Wikibase\DataModel\Entity\EntityIdValue;
use Wikibase\DataModel\Entity\PropertyId;
use Wikibase\PropertySomeValueSnak;
use Wikibase\PropertyValueSnak;
use Wikibase\Query\DIC\ExtensionAccess;
use Wikibase\Query\QueryEntity;
use Wikibase\Query\QueryEntityDeserializer;
use Wikibase\Query\QueryEntitySerializer;
use Wikibase\Query\QueryId;
use Wikibase\Repo\WikibaseRepo;
use Wikibase\SnakList;

/**
 * @group WikibaseQuery
 * @group WikibaseQueryIntegration
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class QueryEntityRoundtripTest extends \PHPUnit_Framework_TestCase {

	public function testQueryEntitySerializationDeserializationRoundtrip() {
		$queryEntity = $this->newQueryEntity();

		$askSerializerFactory = new SerializerFactory();

		$askDeserializerFactory = new DeserializerFactory( WikibaseRepo::getDefaultInstance()->getDataValueFactory() );

		$serializer = new QueryEntitySerializer( $askSerializerFactory->newQuerySerializer() );
		$deserializer = new QueryEntityDeserializer( $askDeserializerFactory->newQueryDeserializer() );

		$serialization = $serializer->serialize( $queryEntity );
		$deserialization = $deserializer->deserialize( $serialization );

		$this->assertEquals( $queryEntity, $deserialization );
	}

	protected function newQueryEntity() {
		$awesomePropertyId = new EntityIdValue( new PropertyId( 'P42' ) );

		$query = new Query(
			new SomeProperty(
				$awesomePropertyId,
				new Disjunction( array(
					new ValueDescription( new StringValue( 'foo' ) ),
					new ValueDescription( new StringValue( 'bar' ) ),
				) )
			),
			array(
				new PropertySelection( $awesomePropertyId )
			),
			new QueryOptions( 10, 0 )
		);

		$queryEntity = new QueryEntity( $query );

		$queryEntity->setId( new QueryId( 'Y1337' ) );

		$queryEntity->setLabel( 'en', 'Awesome' );
		$queryEntity->setLabel( 'de', 'Awesome' );
		$queryEntity->setDescription( 'en', 'ohi' );
		$queryEntity->setDescription( 'de', 'there' );
		$queryEntity->addAliases( 'en', array( 'foo', 'bar' ) );
		$queryEntity->addAliases( 'nl', array( 'baz', 'hax' ) );

		$claim = new Claim(
			new PropertySomeValueSnak( 42 )
		);

		$claim->setGuid( 'foo' );

		$queryEntity->addClaim( $claim );

		$secondClaim = new Claim(
			new PropertyValueSnak( 42, new StringValue( 'baz' ) )
		);

		$secondClaim->setGuid( 'bar' );

		$queryEntity->addClaim( $secondClaim );

		$thirdClaim = new Claim(
			new PropertyValueSnak( 123, new StringValue( 'baz' ) ),
			new SnakList( array(
				new PropertySomeValueSnak( 42 ),
				new PropertySomeValueSnak( 43 )
			) )
		);

		$thirdClaim->setGuid( 'baz' );

		$queryEntity->addClaim( $thirdClaim );

		return $queryEntity;
	}

}
