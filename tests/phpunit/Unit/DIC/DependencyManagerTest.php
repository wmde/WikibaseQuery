<?php

namespace Tests\Unit\Wikibase\Query\DIC;

use Wikibase\Query\DIC\DependencyManager;

/**
 * @covers Wikibase\Query\DIC\DependencyManager
 *
 * @group WikibaseQuery
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class DependencyManagerTest extends \PHPUnit_Framework_TestCase {

	public function testCanConstruct() {
		new DependencyManager();
		$this->assertTrue( true );
	}

	public function testRegisterBuilder() {
		$dependencyManager = new DependencyManager();

		$fooBuilder = $this->getMock( 'Wikibase\Query\DIC\DependencyBuilder' );

		$dependencyManager->registerBuilder( 'foo', $fooBuilder );

		$this->assertTrue( true );
	}

	public function testNewObject() {
		$dependencyManager = new DependencyManager();

		$expectedObject = new \stdClass();
		$expectedObject->awesomeness = 9001;

		$fooBuilder = $this->getMock( 'Wikibase\Query\DIC\DependencyBuilder' );
		$fooBuilder->expects( $this->once() )
			->method( 'buildObject' )
			->with( $this->equalTo( $dependencyManager ) )
			->will( $this->returnValue( $expectedObject ) );

		$dependencyManager->registerBuilder( 'foo', $fooBuilder );
		$actualObject = $dependencyManager->newObject( 'foo' );

		$this->assertEquals( $expectedObject, $actualObject );
	}

	/**
	 * @dataProvider nonStringProvider
	 */
	public function testRegisterBuilderRequiresNonEmptyStringKey( $nonString ) {
		$dependencyManager = new DependencyManager();

		$fooBuilder = $this->getMock( 'Wikibase\Query\DIC\DependencyBuilder' );

		$this->setExpectedException( 'InvalidArgumentException' );
		$dependencyManager->registerBuilder( $nonString, $fooBuilder );
	}

	/**
	 * @dataProvider nonStringProvider
	 */
	public function testNewObjectRequiresNonEmptyStringKey( $nonString ) {
		$dependencyManager = new DependencyManager();

		$this->setExpectedException( 'InvalidArgumentException' );
		$dependencyManager->newObject( $nonString );
	}

	public function nonStringProvider() {
		return array(
			array( 4 ),
			array( 4.2 ),
			array( null ),
			array( array() ),
			array( true ),
			array( '' )
		);
	}

	public function testNewObjectWithNonRegisteredKey() {
		$dependencyManager = new DependencyManager();

		$this->setExpectedException( 'OutOfBoundsException' );

		$dependencyManager->newObject( 'notRegistered' );
	}

}
