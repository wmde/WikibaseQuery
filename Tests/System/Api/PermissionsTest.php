<?php

namespace Tests\Integration\Wikibase\Query\Api;

use DataValues\StringValue;
use Wikibase\DataModel\Entity\ItemId;
use Wikibase\DataModel\Entity\PropertyId;
use Wikibase\Item;
use Wikibase\Property;
use Wikibase\PropertyContent;
use Wikibase\PropertyValueSnak;
use Wikibase\Query\DIC\ExtensionAccess;
use Wikibase\Statement;
use Wikibase\Test\Api\PermissionsTestCase;

/**
 * @group WikibaseQuery
 * @group WikibaseQuerySystem
 * @group Database
 * @group large
 * @group Api
 *
 * @licence GNU GPL v2+
 * @author Adam Shorland
 */
class PermissionsTest extends PermissionsTestCase {

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
		$storeUpdater = $this->getQueryStore()->newWriter();

		$item = $this->newMockItem();

		$storeUpdater->insertEntity( $item );
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

	/**
	 * @dataProvider provideTestEntitiesByPropertyValue
	 */
	public function testEntitiesByPropertyValue( $permissions, $expectedError ) {
		$params = array(
			'property' => self::PROPERTY_ID_STRING,
			'value' => '{"value":"API tests really suck","type":"string"}',
		);

		$this->doPermissionsTest( 'entitiesbypropertyvalue', $params, $permissions, $expectedError );
		//TODO the below check should be pushed into the above method
		if( $expectedError === null ) {
			$this->assertTrue( true );
		}
	}

	public static function provideTestEntitiesByPropertyValue() {
		return array(
			array( //0
				null, // normal permissions
				null // no error
			),

			array( //1
				array( // permissions
					'*'    => array( 'wikibase-query-run' => false ),
					'user' => array( 'wikibase-query-run' => false )
				),
				'permissiondenied' // error
			),
		);
	}

} 