<?php

namespace Tests\Integration\Wikibase\Query\Api;

use DataValues\StringValue;
use Wikibase\DataModel\Entity\ItemId;
use Wikibase\DataModel\Entity\PropertyId;
use Wikibase\Item;
use Wikibase\ItemContent;
use Wikibase\Property;
use Wikibase\PropertyContent;
use Wikibase\PropertyValueSnak;
use Wikibase\Query\DIC\ExtensionAccess;
use Wikibase\Statement;

/**
 * @group WikibaseQuery
 * @group WikibaseQuerySystem
 * @group Database
 * @group medium
 * @group Api
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class EntitiesByPropertyValueApiTest extends \ApiTestCase {

	const MODULE_NAME = 'entitiesbypropertyvalue';
	const PROPERTY_ID_STRING = 'P31337';
	const ITEM_ID_STRING = 'Q42';

	protected $itemId;
	protected $propertyId;

	protected function getQueryStore() {
		return ExtensionAccess::getWikibaseQuery()->getQueryStoreWithDependencies();
	}

	public function setUp() {
		parent::setUp();

		$this->itemId = new ItemId( self::ITEM_ID_STRING );
		$this->propertyId = new PropertyId( self::PROPERTY_ID_STRING );

		$this->getQueryStore()->newInstaller()->install();

		$this->createNewProperty();
		$this->insertNewItem();
	}

	public function tearDown() {
		$this->getQueryStore()->newUninstaller()->uninstall();
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
		$item = $this->newMockItem();

		$itemContent = ItemContent::newFromItem( $item );
		$itemContent->save();
	}

	protected function newMockItem() {
		$item = Item::newEmpty();

		$item->setId( $this->itemId );

		$claim = new Statement(
			new PropertyValueSnak(
				$this->propertyId,
				$this->newMockValue()
			)
		);

		$claim->setGuid( 'foo' );

		$item->addClaim( $claim );

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
