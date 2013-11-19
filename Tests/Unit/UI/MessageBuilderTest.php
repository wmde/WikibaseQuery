<?php

namespace Tests\Unit\Wikibase\Query\UI;

use Wikibase\Query\UI\SearchFormBuilder;

/**
 * @covers Wikibase\Query\UI\SearchFormBuilder
 *
 * @group WikibaseQuery
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class SearchFormBuilderTest extends \PHPUnit_Framework_TestCase {

	public function testGivenEmptyValues_buildSearchFormReturnsString() {
		$searchForm = new SearchFormBuilder(
			'http://foo.bar.baz',
			$this->newMockMessageTextBuilder()
		);

		$fieldValues = array(
			'property' => '',
			'valuejson' => '',
		);

		$this->assertInternalType( 'string', $searchForm->buildSearchForm( $fieldValues ) );
	}

	private function newMockMessageTextBuilder() {
		$builder = $this->getMockBuilder( 'Wikibase\Query\MessageTextBuilder' )
			->disableOriginalConstructor()->getMock();

		$builder->expects( $this->any() )
			->method( 'msgText' )
			->will( $this->returnValue( '' ) );

		return $builder;
	}

}
