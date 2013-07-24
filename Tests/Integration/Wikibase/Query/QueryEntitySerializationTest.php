<?php

namespace Tests\Integration\Wikibase\Query;

use Ask\Language\Description\Disjunction;
use Ask\Language\Description\SomeProperty;
use Ask\Language\Description\ValueDescription;
use Ask\Language\Option\QueryOptions;
use Ask\Language\Query;
use Ask\Language\Selection\PropertySelection;
use Ask\SerializerFactory;
use DataValues\StringValue;
use Wikibase\Claim;
use Wikibase\EntityId;
use Wikibase\PropertySomeValueSnak;
use Wikibase\PropertyValueSnak;
use Wikibase\Query\DIC\ExtensionAccess;
use Wikibase\Query\QueryEntity;
use Wikibase\Query\QueryEntitySerializer;
use Wikibase\SnakList;

/**
 * @file
 * @ingroup WikibaseQuery
 * @group WikibaseQuery
 * @group WikibaseQueryIntegration
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class QueryEntitySerializationTest extends \PHPUnit_Framework_TestCase {

	public function testQueryEntitySerialization() {
		$queryEntity = $this->newQueryEntity();

		$askSerializerFactory = new SerializerFactory();

		$serializer = new QueryEntitySerializer( $askSerializerFactory->newQuerySerializer() );

		$actualSerialization = $serializer->serialize( $queryEntity );

		$this->assertEquals( $this->newQueryEntitySerialization(), $actualSerialization );
	}

	protected function newQueryEntity() {
		$awesomePropertyId = new EntityId( 'property', 42 );

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

		$queryEntity->setId( 1337 );

		$queryEntity->setLabel( 'en', 'Awesome' );
		$queryEntity->setLabel( 'de', 'Awesome' );
		$queryEntity->setDescription( 'en', 'ohi' );
		$queryEntity->setDescription( 'de', 'there' );
		$queryEntity->addAliases( 'en', array( 'foo', 'bar' ) );
		$queryEntity->addAliases( 'nl', array( 'baz', 'hax' ) );

		$queryEntity->addClaim( new Claim(
			new PropertySomeValueSnak( 42 )
		) );

		$queryEntity->addClaim( new Claim(
			new PropertyValueSnak( 42, new StringValue( 'baz' ) )
		) );

		$queryEntity->addClaim( new Claim(
			new PropertyValueSnak( 123, new StringValue( 'baz' ) ),
			new SnakList( array(
				new PropertySomeValueSnak( 42 ),
				new PropertySomeValueSnak( 43 )
			) )
		) );

		return $queryEntity;
	}

	private function newQueryEntitySerialization() {
		$awesomePropertyId = new EntityId( 'property', 42 );

		return array(
			'entity' => array( 'query', 1337 ),

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
					'm' => array( 'value', 42, 'string', 'baz' ),
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

			'query' => array(
				'objectType' => 'query',
				'description' => array(
					'objectType' => 'description',
					'descriptionType' => 'someProperty',
					'value' => array(
						'property' => $awesomePropertyId->toArray(),
						'description' => array(
							'objectType' => 'description',
							'descriptionType' => 'disjunction',
							'value' => array(
								'descriptions' => array(
									array(
										'objectType' => 'description',
										'descriptionType' => 'valueDescription',
										'value' => array(
											'comparator' => 'equal',
											'value' => array(
												'type' => 'string',
												'value' => 'foo',
											)
										),
									),
									array(
										'objectType' => 'description',
										'descriptionType' => 'valueDescription',
										'value' => array(
											'value' => array(
												'type' => 'string',
												'value' => 'bar',
											),
											'comparator' => 'equal',
										),
									)
								)
							)
						),
						'isSubProperty' => false,
					)
				),
				'selectionRequests' => array(
					array(
						'objectType' => 'selectionRequest',
						'selectionRequestType' => 'property',
						'value' => array(
							'property' => $awesomePropertyId->toArray()
						),
					)
				),
				'options' => array(
					'objectType' => 'queryOptions',
					'limit' => 10,
					'offset' => 0,
					'sort' => array(
						'expressions' => array()
					)
				),
			),
		);
	}

}
