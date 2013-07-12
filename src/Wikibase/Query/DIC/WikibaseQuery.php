<?php

namespace Wikibase\Query\DIC;

/**
 * This class exposes methods to retrieve each type of generally accessible object
 * from the dependency manager. This is the only class that should retrieve objects
 * from the dependency manager.
 *
 * It is responsible for having the dependency manager construct the appropriate
 * object, or for retrieving it from an in-object cache where applicable.
 *
 * Class based on suggestions by Tobias Schlitt.
 *
 * @since 1.0
 *
 * @file
 * @ingroup WikibaseQuery
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class WikibaseQuery {

	protected $dependencyManager;

	public function __construct( DependencyManager $dependencyManager ) {
		$this->dependencyManager = $dependencyManager;
	}

	/**
	 * @return mixed
	 */
	public function getByPropertyValueEntityFinder() {
		return $this->dependencyManager->newObject( 'byPropertyValueEntityFinder' );
	}

}