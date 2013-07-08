<?php

namespace Wikibase\Test;

use Wikibase\Query\QueryContent;

/**
 * @covers Wikibase\Query\QueryHandler
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
class QueryHandlerTest extends EntityHandlerTest {

	/**
	 * @see EntityHandlerTest::getModelId
	 * @return string
	 */
	public function getModelId() {
		return CONTENT_MODEL_WIKIBASE_QUERY;
	}

	/**
	 * @see EntityHandlerTest::getClassName
	 * @return string
	 */
	public function getClassName() {
		return '\Wikibase\Query\QueryHandler';
	}

	/**
	 * @see EntityHandlerTest::contentProvider
	 */
	public function contentProvider() {
		$contents = parent::contentProvider();

		/**
		 * @var QueryContent $content
		 */
		$content = clone $contents[1][0];
		// TODO: add some query-specific stuff: $content->getQuery()->;
		$contents[] = array( $content );

		return $contents;
	}

}