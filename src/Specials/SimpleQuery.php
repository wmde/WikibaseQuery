<?php

namespace Wikibase\Query\Specials;

use Wikibase\Query\DIC\ExtensionAccess;
use Wikibase\Query\MessageBuilder;
use Wikibase\Query\MessageTextBuilder;
use Wikibase\Query\UI\SearchFormBuilder;
use Wikibase\Repo\Specials\SpecialWikibaseQueryPage;

/**
 * Special page that allows for querying for all entities with at least one PropertySnak using a
 * certain property and a certain value.
 *
 * @since 0.1
 * @licence GNU GPL v2+
 * @author Daniel Werner < daniel.werner@wikimedia.de >
 */
class SimpleQuery extends SpecialWikibaseQueryPage {

	protected $propertyId;
	protected $valueJson;

	public function __construct() {
		parent::__construct( 'SimpleQuery', 'wikibase-query-run' );
	}

	/**
	 * @see SpecialWikibasePage::execute
	 *
	 * @since 0.1
	 */
	public function execute( $subPage ) {
		if( !parent::execute( $subPage ) ) {
			return false;
		}

		$this->extractRequestFields();

		$this->getOutput()->addModules( 'wikibase.query.special.simplequery' );

		$this->addSearchForm();
		$this->showQuery();

		return true;
	}

	private function extractRequestFields() {
		$request = $this->getRequest();

		$this->propertyId = $request->getText( 'property' );
		$this->valueJson = $request->getText( 'valuejson' );
	}

	private function addSearchForm() {
		$this->newFormBuilder()->buildSearchForm();
	}

	private function newFormBuilder() {
		return new SearchFormBuilder(
			$this->getContext(),
			new MessageTextBuilder( new MessageBuilder( $this->getContext(), !$this->including() ) )
		);
	}

	/**
	 * @see SpecialWikibaseQueryPage::getResult
	 *
	 * @since 0.1
	 */
	protected function getResult( $offset = 0, $limit = 0 ) {
		try {
			$entityFinder = ExtensionAccess::getWikibaseQuery()->getByPropertyValueEntityFinder();
			$entityIds = $entityFinder->findEntities( array(
				'property' => $this->propertyId,
				'value' => $this->valueJson,
				'limit' => (string)$this->limit,
				'offset' => (string)$this->offset
			) );
		} catch( \InvalidArgumentException $e ) {
			// TODO: Display some useful error to the user. For this to be implemented, the base
			//  class had to be refactored and more specific exception implementations would be
			//  useful as well.
			return array();
		}

		return $entityIds;
	}

}
