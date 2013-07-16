<?php

namespace Tests\Integration\Wikibase\Query;

use Wikibase\Query\DIC\ExtensionAccess;

/**
 * @file
 * @ingroup WikibaseQuery
 * @group WikibaseQuery
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class ObjectConstructionTest extends \PHPUnit_Framework_TestCase {

	public function testConstructQueryStore() {
		$queryStore = ExtensionAccess::getWikibaseQuery()->getQueryStore();
		$this->assertInstanceOf( 'Wikibase\QueryEngine\QueryStore', $queryStore );
	}

}
