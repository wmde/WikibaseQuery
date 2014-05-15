<?php

namespace Tests\System\Wikibase\Query\Api;

use DataValues\StringValue;
use User;
use Wikibase\DataModel\Entity\ItemId;
use Wikibase\DataModel\Entity\PropertyId;
use Wikibase\Item;
use Wikibase\Property;
use Wikibase\PropertyValueSnak;
use Wikibase\Query\DIC\ExtensionAccess;
use Wikibase\Repo\WikibaseRepo;
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

		$this->createNewProperty();
		$this->insertNewItem();
	}

	protected function getQueryStore() {
		return ExtensionAccess::getWikibaseQuery()->getQueryStoreWithDependencies();
	}

	protected function createNewProperty() {
		$property = Property::newEmpty();
		$property->setId( $this->propertyId );
		$property->setDataTypeId( 'string' );

		$this->storeEntity( $property );
	}

	protected function insertNewItem() {
		$item = $this->newMockItem();

		$this->storeEntity( $item );
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

	private function storeEntity( $entity ) {
		$entityStore = WikibaseRepo::getDefaultInstance()->getEntityStore();
		$entityStore->saveEntity( $entity, '', new User() );
	}
}
