<?php

namespace Tests\Unit\Wikibase\Query;

use Wikibase\Query\QueryId;

/**
 * @covers Wikibase\Query\QueryId
 *
 * @group Wikibase
 * @group WikibaseQuery
 * @group EntityIdTest
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class ItemIdTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @dataProvider idSerializationProvider
	 */
	public function testCanConstructId( $idSerialization ) {
		$id = new QueryId( $idSerialization );

		$this->assertEquals(
			strtoupper( $idSerialization ),
			$id->getSerialization()
		);
	}

	public function idSerializationProvider() {
		return array(
			array( 'y1' ),
			array( 'y100' ),
			array( 'y1337' ),
			array( 'y31337' ),
			array( 'Y31337' ),
			array( 'Y42' ),
		);
	}

	/**
	 * @dataProvider invalidIdSerializationProvider
	 */
	public function testCannotConstructWithInvalidSerialization( $invalidSerialization ) {
		$this->setExpectedException( 'InvalidArgumentException' );
		new QueryId( $invalidSerialization );
	}

	public function invalidIdSerializationProvider() {
		return array(
			array( 'y' ),
			array( 'p1' ),
			array( 'yy1' ),
			array( '1y' ),
			array( 'y01' ),
			array( 'y 1' ),
			array( ' y1' ),
			array( 'y1 ' ),
			array( '1' ),
			array( ' ' ),
			array( '' ),
			array( '0' ),
			array( 0 ),
			array( 1 ),
		);
	}

}
