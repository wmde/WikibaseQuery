<?php

namespace Wikibase\Query\UI;

use Html;
use InvalidArgumentException;
use Title;
use Wikibase\Query\MessageTextBuilder;

/**
 * @since 0.1
 * @licence GNU GPL v2+
 * @author Daniel Werner < daniel.werner@wikimedia.de >
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class SearchFormBuilder {

	private $localUrl;
	private $messageBuilder;

	/**
	 * @param string $localUrl
	 * @param MessageTextBuilder $messageBuilder
	 *
	 * @throws InvalidArgumentException
	 */
	public function __construct( $localUrl, MessageTextBuilder $messageBuilder ) {
		if ( !is_string( $localUrl ) ) {
			throw new InvalidArgumentException( '$localUrl must be a string' );
		}

		$this->localUrl = $localUrl;
		$this->messageBuilder = $messageBuilder;
	}

	/**
	 * Creates HTML for a search form suitable for the special page's purpose.
	 *
	 * @since 0.1
	 *
	 * @param array $formFieldValues
	 * @return string
	 */
	public function buildSearchForm( array $formFieldValues ) {
		return
			Html::openElement(
				'form',
				array(
					'action' => $this->localUrl,
					'name' => 'simplequery',
					'id' => 'wb-SimpleQuery-form'
				)
			) .
			Html::openElement( 'fieldset' ) .
			Html::element(
				'legend',
				array(),
				$this->messageBuilder->msgText( 'wikibase-specialsimplequery-legend' )
			) .

			$this->buildSearchFormInput( 'property', $formFieldValues['property'] ) .
			$this->buildSearchFormInput( 'valuejson', $formFieldValues['valuejson'] ) .

			Html::input(
				null,
				$this->messageBuilder->msgText( 'wikibase-entitieswithoutlabel-submit' ),
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
	private function buildSearchFormInput( $purpose, $value ) {
		return
			Html::openElement( 'p' ) .
			Html::element(
				'label',
				array(
					'for' => "wb-specialsimplequery-$purpose"
				),
				$this->messageBuilder->msgText( "wikibase-specialsimplequery-label-$purpose" )
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