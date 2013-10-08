<?php

namespace Wikibase\Query\Specials;

use Wikibase\Query\DIC\ExtensionAccess;
use Wikibase\Lib\Specials\SpecialWikibaseQueryPage;
use Html;

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
		parent::__construct( 'SimpleQuery' );
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

		$output = $this->getOutput();
		$request = $this->getRequest();

		$this->propertyId = $request->getText( 'property' );
		$this->valueJson = $request->getText( 'valuejson' );

		$output->addHTML(
			$this->buildSearchForm( array(
				'property' => $this->propertyId,
				'valuejson' => $this->valueJson
			) )
		);

		$this->showQuery();
		return true;
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

	/**
	 * Creates HTML for a search form suitable for the special page's purpose.
	 *
	 * @since 0.1
	 *
	 * @param array $formFieldValues
	 * @return string
	 */
	protected function buildSearchForm( array $formFieldValues ) {
		return
			Html::openElement(
				'form',
				array(
					'action' => $this->getTitle()->getLocalURL(),
					'name' => 'simplequery',
					'id' => 'wb-SimpleQuery-form'
				)
			) .
			Html::openElement( 'fieldset' ) .
			Html::element(
				'legend',
				array(),
				$this->msg( 'wikibase-specialsimplequery-legend' )->text()
			) .

			$this->buildSearchFormInput( 'property', $formFieldValues['property'] ) .
			$this->buildSearchFormInput( 'valuejson', $formFieldValues['valuejson'] ) .

			Html::input(
				null,
				$this->msg( 'wikibase-entitieswithoutlabel-submit' )->text(),
				'submit',
				array(
					'id' => 'wikibase-specialsimplequery-submit',
					'class' => 'wb-input-button'
				)
			) .

			Html::closeElement( 'fieldset' ) .
			Html::closeElement( 'form' );
	}

	/**
	 * Creates HTML for an input field.
	 *
	 * @since 0.1
	 *
	 * @param string $purpose
	 * @param string $value
	 * @return string
	 */
	protected function buildSearchFormInput( $purpose, $value ) {
		return
			Html::openElement( 'p' ) .
			Html::element(
				'label',
				array(
					'for' => "wb-specialsimplequery-$purpose"
				),
				$this->msg( "wikibase-specialsimplequery-label-$purpose" )->text()
			) .
			Html::input(
				$purpose,
				$value,
				'text',
				array(
					'id' => "wb-specialsimplequery-$purpose"
				)
			) .
			Html::closeElement( 'p' );
	}
}
