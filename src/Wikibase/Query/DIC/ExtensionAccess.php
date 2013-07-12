<?php

namespace Wikibase\Query\DIC;

/**
 * Static access to the dependency injection container of WikibaseQuery.
 *
 * Usage of this class is only allowed at entry points, such as
 * hook handlers, API modules and special pages.
 *
 * @since 1.0
 *
 * @file
 * @ingroup WikibaseQuery
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class ExtensionAccess {

	private static $registry;

	public static function setRegistry( ExtensionRegistry $registry ) {
		self::$registry = $registry;
	}

	/**
	 * @return ExtensionRegistry
	 */
	public static function getRegistry() {
		return self::$registry;
	}

}
