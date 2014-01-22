<?php

namespace Wikibase\Query\DIC;

/**
 * Static access to the dependency injection container of WikibaseQuery.
 *
 * Usage of this class is only allowed at entry points, such as
 * hook handlers, API modules and special pages.
 *
 * Class based on suggestions by Tobias Schlitt.
 *
 * @since 0.1
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class ExtensionAccess {

	private static $registry = null;
	private static $registryBuilder;

	public static function setRegistryBuilder( $registryBuilder ) {
		self::$registryBuilder = $registryBuilder;
	}

	/**
	 * @return WikibaseQuery
	 */
	public static function getWikibaseQuery() {
		if ( self::$registry === null ) {
			self::buildRegistry();
		}

		return self::$registry;
	}

	protected static function buildRegistry() {
		self::$registry = call_user_func( self::$registryBuilder );
	}

}
