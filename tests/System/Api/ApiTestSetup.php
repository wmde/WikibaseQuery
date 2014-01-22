<?php

namespace Tests\System\Wikibase\Query\Api;

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
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 * @author Adam Shorland
 */
class ApiTestSetup {

	protected $propertyId;
	protected $itemId;

	function __construct( $itemId, $propertyId ) {
		$this->itemId = $itemId;
		$this->propertyId = $propertyId;
	}

	public function setUp() {
		$this->itemId = new ItemId( $this->itemId );
		$this->propertyId = new PropertyId( $this->propertyId );

		$this->getQueryStore()->newInstaller()->install();

		$this->createNewProperty();
		$this->insertNewItem();
	}

	public function tearDown() {
		$this->getQueryStore()->newUninstaller()->uninstall();
	}

	protected function getQueryStore() {
		return ExtensionAccess::getWikibaseQuery()->getQueryStoreWithDependencies();
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

} 