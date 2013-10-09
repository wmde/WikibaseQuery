<?php

namespace Tests\Unit\Wikibase\Query\DIC;

use Wikibase\Query\DIC\WikibaseQueryBuilder;

/**
 * @covers Wikibase\Query\Setup\WikibaseQueryBuilder
 *
 * @group Wikibase
 * @group WikibaseQuery
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class WikibaseQueryBuilderTest extends \PHPUnit_Framework_TestCase {

	public function testReturnsWikibaseQuery() {
		$builder = new WikibaseQueryBuilder();

		$this->assertInstanceOf(
			'Wikibase\Query\DIC\WikibaseQuery',
			$builder->build()
		);
	}

}
