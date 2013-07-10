<?php

namespace Wikibase\Query\DIC;

/**
 * @since 1.0
 *
 * @file
 * @ingroup WikibaseQuery
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
abstract class DependencyBuilder {

	public abstract function buildObject( DependencyManager $dependencyManager );

}
