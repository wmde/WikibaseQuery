<?php

namespace Wikibase\Query\DIC;

/**
 * Derivatives are responsible for realizing the creation of part of the object
 * graph. In other words, each can construct one specific type of object and
 * provide it with all its dependencies.
 *
 * The dependency manager is passed along so construction of dependencies can
 * be delegated.
 *
 * Class based on suggestions by Tobias Schlitt.
 *
 * @since 0.1
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
abstract class DependencyBuilder {

	/**
	 * @param DependencyManager $dependencyManager
	 *
	 * @return mixed
	 */
	public abstract function buildObject( DependencyManager $dependencyManager );

}
