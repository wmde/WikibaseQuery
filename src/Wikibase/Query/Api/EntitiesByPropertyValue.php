<?php

namespace Wikibase\Query\Api;

use ApiBase;
use InvalidArgumentException;
use Wikibase\Lib\PropertyNotFoundException;
use Wikibase\Query\DIC\ExtensionAccess;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class EntitiesByPropertyValue extends \ApiBase {

	const ERR_NO_SUCH_PROPERTY = 'The specified property does not exist';
	const ERR_INVALID_JSON = 'The provided value needs to be a serialization of a DataValue';

	/**
	 * @see ApiBase::execute
	 *
	 * @since 0.1
	 */
	public function execute() {
		$entityFinder = ExtensionAccess::getWikibaseQuery()->getByPropertyValueEntityFinder();

		try {
			$entityIds = $entityFinder->findEntities( $this->extractRequestParams() );
		}
		catch ( InvalidArgumentException $ex ) {
			$this->dieUsage(
				self::ERR_INVALID_JSON,
				'invalid-json'
			);
		}
		catch ( PropertyNotFoundException $ex ) {
			$this->dieUsage(
				self::ERR_NO_SUCH_PROPERTY,
				'no-such-property'
			);
		}

		if ( isset( $entityIds ) ) {
			$this->getResult()->addValue( null, 'entities', $entityIds );
		}
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
			'limit' => array(
				ApiBase::PARAM_TYPE => 'string',
				ApiBase::PARAM_REQUIRED => false,
				ApiBase::PARAM_DFLT => 10,
				ApiBase::PARAM_MAX => ApiBase::LIMIT_SML1, // TODO: policy decision
				ApiBase::PARAM_MIN => 0,
				ApiBase::PARAM_RANGE_ENFORCE => true,
			),
			'offset' => array(
				ApiBase::PARAM_TYPE => 'string',
				ApiBase::PARAM_REQUIRED => false,
				ApiBase::PARAM_DFLT => 0,
				ApiBase::PARAM_MAX => ApiBase::LIMIT_SML1, // TODO: policy decision
				ApiBase::PARAM_MIN => 0,
				ApiBase::PARAM_RANGE_ENFORCE => true,
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
	 * @see ApiBase::getPossibleErrors
	 *
	 * @since 0.1
	 *
	 * @return array
	 */
	public function getPossibleErrors() {
		return array(
			array(
				'code' => 'invalid-json',
				'info' => self::ERR_INVALID_JSON,
			),
			array(
				'code' => 'no-such-property',
				'info' => self::ERR_NO_SUCH_PROPERTY,
			)
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
