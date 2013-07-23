<?php

namespace Tests\Phpunit\Wikibase\Query;

use Wikibase\Query\QueryEntityDeserializer;

/**
 * @covers Wikibase\Query\QueryEntityDeserializer
 *
 * @file
 * @ingroup WikibaseQuery
 * @group WikibaseQuery
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 * @author Adam Shorland < adamshorland@gmail.com >
 */
class QueryEntityDeserializerTest extends \PHPUnit_Framework_TestCase {

	public function testCanConstruct() {
		new QueryEntityDeserializer();
		$this->assertFalse( false );
	}

}
