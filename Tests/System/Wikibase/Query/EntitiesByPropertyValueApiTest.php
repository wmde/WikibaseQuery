<?php

namespace Tests\Integration\Wikibase\Query;

use DataValues\StringValue;
use Wikibase\EntityId;
use Wikibase\Item;
use Wikibase\Property;
use Wikibase\PropertyContent;
use Wikibase\PropertyValueSnak;
use Wikibase\Query\DIC\ExtensionAccess;
use Wikibase\Statement;

/**
 * @file
 * @ingroup WikibaseQuery
 * @group WikibaseQuery
 * @group WikibaseQuerySystem
 * @group Database
 * @group large
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class EntitiesByPropertyValueApiTest extends \ApiTestCase {

	const MODULE_NAME = 'entitiesByPropertyValue';
	const PROPERTY_ID = 31337;
	const ITEM_ID = 42;
	const PROPERTY_ID_STRING = 'p31337';
	const ITEM_ID_STRING = 'q42';

	protected function getQueryStore() {
		return ExtensionAccess::getWikibaseQuery()->getQueryStore();
	}

	protected function reinitializeStore() {
		$queryStore = $this->getQueryStore();
		$setup = $queryStore->newSetup();

		$setup->uninstall();
		$setup->install();
	}

	public function setUp() {
		parent::setUp();
		$this->reinitializeStore();

		$this->createNewProperty();
		$this->insertNewItem();
	}

	public function tearDown() {
		$this->reinitializeStore();
		parent::tearDown();
	}

	protected function createNewProperty() {
		$property = Property::newEmpty();
		$property->setId( new EntityId( 'property', self::PROPERTY_ID ) );
		$property->setDataTypeId( 'string' );

		$propertyContent = PropertyContent::newFromProperty( $property );

		$propertyContent->save();
	}

	protected function insertNewItem() {
		$storeUpdater = $this->getQueryStore()->getUpdater();

		$item = $this->newMockItem();

		$storeUpdater->insertEntity( $item );
	}

	protected function newMockItem() {
		$item = Item::newEmpty();

		$item->setId( new EntityId( 'item', self::ITEM_ID ) );

		$item->addClaim( new Statement(
			new PropertyValueSnak(
				new EntityId( 'property', self::PROPERTY_ID ),
				$this->newMockValue()
			)
		) );

		return $item;
	}

	protected function newMockValue() {
		return new StringValue( 'API tests really suck' );
	}

	protected function newMockValueString() {
		return '{"value":"API tests really suck","type":"string"}';
	}

	public function testMakeApiRequest() {
		$resultArray = $this->getResultForRequestWithValue( $this->newMockValueString() );

		$this->assertArrayHasKey( 'entities', $resultArray );

		$entities = $resultArray['entities'];

		$this->assertEquals( array( self::ITEM_ID_STRING ), $entities );
	}

	protected function getResultForRequestWithValue( $value ) {
		$params = array(
			'action' => self::MODULE_NAME,
			'property' => self::PROPERTY_ID_STRING,
			'value' => $value,
		);

		return $this->getResultForRequest( $params );
	}

	protected function getResultForRequest( array $requestParams ) {
		list( $resultArray, ) = $this->doApiRequest( $requestParams );

		return $resultArray;
	}

	public function testMakeRequestWithInvalidValue() {
		$this->setExpectedException(
			'UsageException',
			'The provided value needs to be a serialization of a DataValue'
		);

		$this->getResultForRequestWithValue(
			'Im an invalid value in your API request!'
		);
	}

	public function testMakeRequestWithUnknownProperty() {
		$this->setExpectedException(
			'UsageException',
			'The specified property does not exist'
		);

		$this->getResultForRequest( array(
			'action' => self::MODULE_NAME,
			'property' => 'p45831890',
			'value' => $this->newMockValueString(),
		) );
	}

}
