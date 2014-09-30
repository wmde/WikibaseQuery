<?php

namespace Wikibase\Query\Api;

use ApiBase;
use InvalidArgumentException;
use Wikibase\DataModel\Entity\EntityId;
use Wikibase\DataModel\Entity\PropertyNotFoundException;
use Wikibase\Query\DIC\ExtensionAccess;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class EntitiesByPropertyValue extends \ApiBase {

	const ERR_NO_SUCH_PROPERTY = 'The specified property does not exist';
	const ERR_INVALID_JSON = 'The provided value needs to be a serialization of a DataValue';
	const ERR_PERMISSION_DENIED = 'You do not have sufficient permissions';

	/**
	 * @see ApiBase::execute
	 *
	 * @since 0.1
	 */
	public function execute() {
		$user = $this->getUser();
		if ( ! $user->isAllowed( 'wikibase-query-run' ) ){
			$this->dieUsage(
				self::ERR_PERMISSION_DENIED,
				'permissiondenied'
			);
		}

		$this->getResult()->addValue(
			null,
			'entities',
			$this->serializeEntityIds( $this->getEntityIds() )
		 );
	}

	private function getEntityIds() {
		$entityFinder = ExtensionAccess::getWikibaseQuery()->getByPropertyValueEntityFinder();

		try {
			return $entityFinder->findEntities( $this->extractRequestParams() );
		}
		catch ( InvalidArgumentException $ex ) {
			// FIXME: one cannot assume here all InvalidArgumentException are caused by invalid json!
			$this->dieUsage(
				$ex->getMessage(),
				'invalid-json'
			);
		}
		catch ( PropertyNotFoundException $ex ) {
			$this->dieUsage(
				self::ERR_NO_SUCH_PROPERTY,
				'no-such-property'
			);
		}
	}

	/**
	 * @param EntityId[] $entityIds
	 *
	 * @return string[]
	 */
	private function serializeEntityIds( array $entityIds ) {
		$formattedIds = array();

		foreach ( $entityIds as $entityId ) {
			$formattedIds[] = $entityId->getSerialization();
		}

		return $formattedIds;
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
			'entitytype' => 'The type of entities to limit the search to',
			'limit' => 'Maximum number of results',
			'offset' => 'When more results are available, use this to continue',
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
			'api.php?action=entitiesbypropertyvalue&property=P42&value={dataValueSerialization}&entitytype=item'
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
