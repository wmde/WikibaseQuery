<?php

namespace Wikibase\Query\UI;

use HTMLForm;
use IContextSource;
use Wikibase\Query\MessageTextBuilder;

/**
 * @since 0.1
 * @licence GNU GPL v2+
 * @author Daniel Werner < daniel.werner@wikimedia.de >
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class SearchFormBuilder {

	private $context;
	private $messageBuilder;
	private $form;

	/**
	 * @param IContextSource $context
	 * @param MessageTextBuilder $this->messageBuilder
	 */
	public function __construct( IContextSource $context, MessageTextBuilder $messageBuilder ) {
		$this->context = $context;
		$this->messageBuilder = $messageBuilder;
	}

	private function getSearchForm( ) {
		if( !$this->form ) {
			$this->form = new HTMLForm( array(
				'property' => array(
					'label' => $this->messageBuilder->msgText( 'wikibase-specialsimplequery-label-property' ),
					'id' => 'wb-specialsimplequery-property',
					'class' => 'HTMLTextField',
					'name' => 'property'
				),
				'valuejson' => array(
					'label' => $this->messageBuilder->msgText( 'wikibase-specialsimplequery-label-valuejson' ),
					'id' => 'wb-specialsimplequery-valuejson',
					'class' => 'HTMLTextField',
					'name' => 'valuejson'
				)
			), $this->context );

			$this->form->setId( 'wb-specialsimplequery-form' );
			$this->form->setMethod( 'get' );
			$this->form->setWrapperLegend( $this->messageBuilder->msgText( 'wikibase-specialsimplequery-legend' ) );
			$this->form->setSubmitText( $this->messageBuilder->msgText( 'wikibase-entitieswithoutlabel-submit' ) );

			$this->form->setSubmitCallback( array( $this, 'processInput' ) );
		}
		return $this->form;
	}

	/**
	 * Creates HTML for a search form suitable for the special page's purpose.
	 *
	 * @since 0.1
	 *
	 * @return string
	 */
	public function buildSearchForm( ) {
		return $this->getSearchForm()->show();
	}

	public function processInput( $formData ) {
		// Always show form, even if it has been submitted successfully
		return false;
	}
}
