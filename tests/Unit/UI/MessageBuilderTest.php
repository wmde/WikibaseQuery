<?php

namespace Tests\Unit\Wikibase\Query\UI;

use FauxRequest;
use RequestContext;
use Title;
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

	public function testGivenEmptyValues_buildSearchFormAddsHtml() {
		$requestContext = $this->newRequestContext( array(
			'property' => '',
			'valuejson' => ''
		) );

		$searchForm = new SearchFormBuilder(
			$requestContext,
			$this->newMockMessageTextBuilder()
		);

		$searchForm->buildSearchForm();
		$this->assertInternalType( 'string', $requestContext->getOutput()->getHTML() );
	}

	private function newRequestContext() {
		$context = new RequestContext();
		$context->setRequest( new FauxRequest() );
		$context->setTitle( Title::makeTitle( NS_MAIN, 'Test' ) );
		return $context;
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
