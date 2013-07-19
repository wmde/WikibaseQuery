<?php

namespace Tests\Phpunit\Wikibase\Query;

use Wikibase\EntityId;
use Wikibase\Query\PropertyDataValueTypeFinder;

/**
 * @covers Wikibase\Query\PropertyDataValueTypeFinder
 *
 * @file
 * @ingroup WikibaseQuery
 * @group WikibaseQuery
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class PropertyDataValueTypeFinderTest extends \PHPUnit_Framework_TestCase {

	public function testGetDataValueTypeForProperty() {
		$propertyId = new EntityId( 'property', 1337 );
		$dataTypeId = 'awesomeType';
		$dataValueType = 'awesomeDvType';

		$dtIdLookup = $this->getMock( 'Wikibase\Lib\PropertyDataTypeLookup' );

		$dtIdLookup->expects( $this->once() )
			->method( 'getDataTypeIdForProperty' )
			->with( $this->equalTo( $propertyId ) )
			->will( $this->returnValue( $dataTypeId ) );

		$dataType = $this->getMockBuilder( 'DataTypes\DataType' )
			->disableOriginalConstructor()->getMock();

		$dataType->expects( $this->once() )
			->method( 'getDataValueType' )
			->will( $this->returnValue( $dataValueType ) );

		$dtFactory = $this->getMock( 'DataTypes\DataTypeFactory' );

		$dtFactory->expects( $this->once() )
			->method( 'getType' )
			->with( $this->equalTo( $dataTypeId ) )
			->will( $this->returnValue( $dataType ) );

		$dvTypeFinder = new PropertyDataValueTypeFinder( $dtIdLookup, $dtFactory );

		$foundDvType = $dvTypeFinder->getDataValueTypeForProperty( $propertyId );

		$this->assertEquals( $dataValueType, $foundDvType );
	}

}
