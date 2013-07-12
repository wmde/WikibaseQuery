<?php

namespace Tests\Phpunit\Wikibase\Query\DIC;

use Wikibase\Query\DIC\ExtensionAccess;

/**
 * @covers Wikibase\Query\DIC\ExtensionAccess
 *
 * @file
 * @ingroup WikibaseQuery
 * @group WikibaseQuery
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class ExtensionAccessTest extends \PHPUnit_Framework_TestCase {

	public function testCanSetAndGetRegistry() {
		$registry = $this->getMockBuilder( 'Wikibase\Query\DIC\WikibaseQuery' )
			->disableOriginalConstructor()->getMock();

		ExtensionAccess::setRegistryBuilder(
			function() use ( $registry ) {
				return $registry;
			}
		);

		$this->assertEquals( $registry, ExtensionAccess::getWikibaseQuery() );
	}

}
