<?php

namespace Wikibase\Query\DIC;

use InvalidArgumentException;
use OutOfBoundsException;

/**
 * This class is responsible of dependency resolution for
 * object construction via the registered dependency builders.
 *
 * This class is internal to the dependency injection mechanism
 * and should not be used directly from the application. All
 * access should happen through the WikibaseQuery.
 *
 * No caching should be done on this level.
 *
 * Class based on suggestions by Tobias Schlitt.
 *
 * @since 0.1
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class DependencyManager {

	/**
	 * @var DependencyBuilder[]
	 */
	private $builders = array();

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

	private function assertIsValidObjectKey( $objectKey ) {
		if ( !is_string( $objectKey ) || $objectKey === '' ) {
			throw new InvalidArgumentException( '$objectKey needs to be a string' );
		}
	}

}
