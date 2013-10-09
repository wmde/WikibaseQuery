<?php

namespace Wikibase\Query\DIC;

use Wikibase\Query\ByPropertyValueEntityFinder;
use Wikibase\QueryEngine\QueryStore;

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
 * @since 0.1
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
	 * @return ByPropertyValueEntityFinder
	 */
	public function getByPropertyValueEntityFinder() {
		return $this->dependencyManager->newObject( 'byPropertyValueEntityFinder' );
	}

	/**
	 * @return QueryStore
	 */
	public function getQueryStore() {
		return $this->dependencyManager->newObject( 'queryStore' );
	}

}