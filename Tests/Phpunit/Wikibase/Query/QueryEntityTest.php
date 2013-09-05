<?php

namespace Tests\Phpunit\Wikibase\Query;

use Ask\Language\Description\AnyValue;
use Ask\Language\Option\QueryOptions;
use Ask\Language\Query;
use Wikibase\Query\QueryEntity;

/**
 * @covers Wikibase\Query\QueryEntity
 *
 * @group WikibaseQuery
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class QueryEntityTest extends \PHPUnit_Framework_TestCase {

	protected function getNewSimpleEntity() {
		return new QueryEntity( $this->newQuery() );
	}

	public function testSetQueryDefinition() {
		$queryEntity = $this->getNewSimpleEntity();

		$query = $this->newQuery();

		$queryEntity->setQuery( $query );

		$obtainedQuery = $queryEntity->getQuery();

		$this->assertInstanceOf( 'Ask\Language\Query', $obtainedQuery );
		$this->assertEquals( $query, $obtainedQuery );
	}

	protected function newQuery() {
		return new Query(
			new AnyValue(),
			array(),
			new QueryOptions( 1, 0 )
		);
	}

	public function testCanConstructWithJustAQuery() {
		new QueryEntity( $this->newQuery() );

		$this->assertTrue( true );
	}

	public function testStubDoesNotMessThingsUp() {
		$queryEntity = $this->getNewSimpleEntity();

		$query = $this->newQuery();

		$queryEntity->setQuery( $query );

		$queryEntity->stub();
		$obtainedQuery = $queryEntity->getQuery();

		$this->assertInstanceOf( 'Ask\Language\Query', $obtainedQuery );
		$this->assertEquals( $query, $obtainedQuery );
	}

}
