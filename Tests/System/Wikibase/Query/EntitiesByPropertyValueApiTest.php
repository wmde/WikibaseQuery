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
 * @group Database
 * @group large
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class EntitiesByPropertyValueApiTest extends \ApiTestCase {

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
	}

	public function tearDown() {
		$this->reinitializeStore();
		parent::tearDown();
	}

	public function testMakeApiRequest() {
		$this->createNewProperty();

		$storeUpdater = $this->getQueryStore()->getUpdater();

		$item = $this->newMockItem();

		$storeUpdater->insertEntity( $item );

		$params = array(
			'action' => 'entitiesByPropertyValue',
			'property' => 'p31337',
			'value' => json_encode( $this->newMockValue()->toArray() ),
		);

		list( $resultArray, ) = $this->doApiRequest( $params );

		$this->assertArrayHasKey( 'entities', $resultArray );

		$entities = $resultArray['entities'];

		$this->assertEquals( array( 'q42' ), $entities );
	}

	protected function createNewProperty() {
		$property = Property::newEmpty();
		$property->setId( new EntityId( 'property', 31337 ) );
		$property->setDataTypeId( 'string' );

		$propertyContent = PropertyContent::newFromProperty( $property );

		$propertyContent->save();
	}

	protected function newMockItem() {
		$item = Item::newEmpty();

		$item->setId( new EntityId( 'item', 42 ) );

		$item->addClaim( new Statement(
			new PropertyValueSnak(
				new EntityId( 'property', 31337 ),
				$this->newMockValue()
			)
		) );

		return $item;
	}

	protected function newMockValue() {
		return new StringValue( 'API tests really suck' );
	}

}
