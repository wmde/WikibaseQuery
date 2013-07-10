<?php

namespace Wikibase\Query\DIC;

use InvalidArgumentException;
use OutOfBoundsException;

/**
 * @since 1.0
 *
 * @file
 * @ingroup WikibaseQuery
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class DependencyManager {

	/**
	 * @var DependencyBuilder[]
	 */
	protected $builders = array();

	public function registerBuilder( $objectKey, DependencyBuilder $builder ) {
		$this->assertIsValidObjectKey( $objectKey );
		$this->builders[$objectKey] = $builder;
	}

	public function newObject( $objectKey ) {
		$this->assertIsValidObjectKey( $objectKey );

		if ( !array_key_exists( $objectKey, $this->builders ) ) {
			throw new OutOfBoundsException( "No '$objectKey' builder has been registered'" );
		}

		return $this->builders[$objectKey]->buildObject( $this );
	}

	protected function assertIsValidObjectKey( $objectKey ) {
		if ( !is_string( $objectKey ) || $objectKey === '' ) {
			throw new InvalidArgumentException( '$objectKey needs to be a string' );
		}
	}

}
