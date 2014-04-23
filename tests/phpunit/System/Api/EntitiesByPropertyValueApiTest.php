<?php

namespace Tests\System\Wikibase\Query\Api;

//TODO FIXME where to put this?
require_once( __DIR__ . '/ApiTestSetup.php' );

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

	/**
	 * @var ApiTestSetup
	 */
	protected $apiTestSetup;

	public function setUp() {
		parent::setUp();
		$this->apiTestSetup = new ApiTestSetup( self::ITEM_ID_STRING, self::PROPERTY_ID_STRING );
		$this->apiTestSetup->setUp();
	}

	public function tearDown() {
		parent::tearDown();
		$this->apiTestSetup->tearDown();
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
