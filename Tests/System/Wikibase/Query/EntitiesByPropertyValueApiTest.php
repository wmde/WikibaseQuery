<?php

namespace Tests\Integration\Wikibase\Query;

use DataValues\StringValue;
use Wikibase\DataModel\Entity\ItemId;
use Wikibase\DataModel\Entity\PropertyId;
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
	const PROPERTY_ID_STRING = 'P31337';
	const ITEM_ID_STRING = 'Q42';

	protected $itemId;
	protected $propertyId;

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

		$this->itemId = new ItemId( self::ITEM_ID_STRING );
		$this->propertyId = new PropertyId( self::PROPERTY_ID_STRING );

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
		$property->setId( $this->propertyId );
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

		$item->setId( $this->itemId );

		$item->addClaim( new Statement(
			new PropertyValueSnak(
				$this->propertyId,
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
