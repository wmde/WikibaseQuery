<?php

namespace Wikibase\Query\UI;

use HTMLForm;
use IContextSource;
use Wikibase\Query\MessageTextBuilder;

// TODO: Move to own file, if it stays
class HTMLFormWithPrePostBody extends HTMLForm {
	protected $mPreBody = '';
	protected $mPostBody = '';

	function setPreBody( $val ) {
		$this->mPreBody = $val;
	}

	function setPostBody( $val ) {
		$this->mPostBody = $val;
	}

	function getBody() {
		return $this->mPreBody . parent::getBody() . $this->mPostBody;
	}
}

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
			$this->form = new HTMLFormWithPrePostBody( array(
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

			/*
			 * FIXME: This is obviously ugly and fragile. The Wikibase CSS and DOM needs
			 * improvement so that it supports this in a simple way.
			 * We might also replace it with something like:
				wfTemplate(
					'wb-claimlistview',
					wfTemplate(
						'wb-claimlistview',
						wfTemplate(
							'wb-claim'
							// FIXME: classes wb-edit wb-new, style="float: none" missing
						)
						// FIXME: classes wb-new missing, style="float: none" missing
					)
				);
			*/
			$wrappers = array(
				// This is like a .wb-entity, but has no space for a toolbar
				'<div style="max-width: 50em; width: 100%">',
				'<div class="wb-claims">',
				'<div class="wb-claimgrouplistview">',
				// No need to float here
				'<div class="wb-claimlistview wb-new" style="float: none">',
				'<div class="wb-claims">',
				'<div class="wb-claimview wb-edit wb-new" style="float: none">',
				'<div class="wb-claim">'
			);

			$this->form->setPreBody( implode( $wrappers, '' ) );
			$this->form->setPostBody( str_repeat( '</div>', count( $wrappers ) ) );
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
