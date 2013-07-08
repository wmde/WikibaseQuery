<?php

namespace Wikibase\Test;

/**
 * @covers Wikibase\Query\QueryContent
 *
 * @file
 * @since 0.1
 *
 * @ingroup WikibaseQuery
 * @group WikibaseQuery
 * @group Database
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class QueryContentTest extends EntityContentTest {

	/**
	 * @see EntityContentTest::getContentClass
	 *
	 * @since 0.1
	 *
	 * @return string
	 */
	protected function getContentClass() {
		$this->markTestSkipped( 'This test still has to be made to work' );
		return '\Wikibase\Query\QueryContent';
	}

}
