<?php

namespace Wikibase\Query\Api;

use ApiBase;
use Wikibase\Query\DIC\ExtensionAccess;

/**
 * @since 0.1
 *
 * @file
 * @ingroup WikibaseQuery
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class EntitiesByPropertyValue extends \ApiBase {

	/**
	 * @see ApiBase::execute
	 *
	 * @since 0.1
	 */
	public function execute() {
		$entityFinder = ExtensionAccess::getWikibaseQuery()->getByPropertyValueEntityFinder();
		$entityFinder->findEntities( $this->extractRequestParams() );

		// TODO: add to API output
		// TODO: system test
	}

	/**
	 * @see ApiBase::getAllowedParams
	 *
	 * @since 0.1
	 *
	 * @return array
	 */
	public function getAllowedParams() {
		return array(
			'property' => array(
				ApiBase::PARAM_TYPE => 'string',
				ApiBase::PARAM_REQUIRED => true,
			),
			'value' => array(
				ApiBase::PARAM_TYPE => 'string',
				ApiBase::PARAM_REQUIRED => true,
			),
			'entityType' => array(
				ApiBase::PARAM_TYPE => 'string',
				ApiBase::PARAM_REQUIRED => false,
				ApiBase::PARAM_DFLT => 'item',
			),
		);
	}

	/**
	 * @see ApiBase::getParamDescription
	 *
	 * @since 0.1
	 *
	 * @return array
	 */
	public function getParamDescription() {
		return array(
			'property' => 'The id of the property for which values should match',
			'value' => 'The value to match against',
			'entityType' => 'The type of entities to limit the search to',
		);
	}

	/**
	 * @see ApiBase::getDescription
	 *
	 * @since 0.1
	 *
	 * @return string
	 */
	public function getDescription() {
		return array(
			'Returns a list of all entity IDs that have a statement where the main snak has the given property and the value is exactly the given value.'
		);
	}

	/**
	 * @see ApiBase::getExamples
	 *
	 * @since 0.1
	 *
	 * @return array
	 */
	protected function getExamples() {
		return array(
			'api.php?action=entitiesbypropertyvalue&property=p42&value={data value serialization}&entityType=item'
			// 'ex' => 'desc' // TODO
		);
	}

	/**
	 * @see ApiBase::getHelpUrls
	 *
	 * @since 0.1
	 *
	 * @return string
	 */
	public function getHelpUrls() {
		return ''; // TODO
	}

}
