<?php

namespace Wikibase\Query;

use InvalidArgumentException;
use Wikibase\DataModel\Entity\EntityId;

/**
 * @since 0.1
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class QueryId extends EntityId {

	const PATTERN = '/^y[1-9][0-9]*$/i';

	/**
	 * @param string $idSerialization
	 *
	 * @throws InvalidArgumentException
	 */
	public function __construct( $idSerialization ) {
		$this->assertValidIdFormat( $idSerialization );

		parent::__construct(
			QueryEntity::ENTITY_TYPE,
			$idSerialization
		);
	}

	protected function assertValidIdFormat( $idSerialization ) {
		if ( !is_string( $idSerialization ) ) {
			throw new InvalidArgumentException( 'The id serialization needs to be a string.' );
		}

		if ( !preg_match( self::PATTERN, $idSerialization ) ) {
			throw new InvalidArgumentException( 'Invalid QueryId serialization provided.' );
		}
	}

}
