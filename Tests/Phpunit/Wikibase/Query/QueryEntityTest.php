<?php

namespace Tests\Phpunit\Wikibase\Query;

use Ask\Language\Description\AnyValue;
use Ask\Language\Option\QueryOptions;
use Ask\Language\Query;
use Wikibase\Query\QueryEntity;
use Wikibase\Test\EntityTest;

/**
 * @covers Wikibase\Query\QueryEntity
 *
 * @file
 * @since 0.1
 *
 * @ingroup WikibaseQuery
 * @group WikibaseQuery
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class QueryEntityTest extends EntityTest {

	/**
	 * @see EntityTest::getNewEmpty
	 *
	 * @since 0.1
	 *
	 * @return Query
	 */
	protected function getNewEmpty() {
		return QueryEntity::newEmpty();
	}

	/**
	 * @see EntityTest::getNewFromArray
	 *
	 * @since 0.1
	 *
	 * @param array $data
	 *
	 * @return QueryEntity
	 */
	protected function getNewFromArray( array $data ) {
		return QueryEntity::newFromArray( $data );
	}

	public function testSetQueryDefinition() {
		$query = QueryEntity::newEmpty();

		$queryDefinition = new Query(
			new AnyValue(),
			array(),
			new QueryOptions( 1, 0 )
		);

		$query->setQuery( $queryDefinition );

		$obtainedDefinition = $query->getQuery();

		$this->assertInstanceOf( 'Ask\Language\Query', $obtainedDefinition );
		$this->assertEquals( $queryDefinition, $obtainedDefinition );
	}

}
