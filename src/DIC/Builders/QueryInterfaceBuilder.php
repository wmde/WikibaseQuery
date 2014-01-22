<?php

namespace Wikibase\Query\DIC\Builders;

use Wikibase\Database\LazyDBConnectionProvider;
use Wikibase\Database\MediaWiki\MediaWikiQueryInterface;
use Wikibase\Database\QueryInterface\QueryInterface;
use Wikibase\Query\DIC\DependencyBuilder;
use Wikibase\Query\DIC\DependencyManager;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class QueryInterfaceBuilder extends DependencyBuilder {

	protected $queryInterfaceBuilderKey;

	public function __construct( $queryInterfaceBuilderKey ) {
		$this->queryInterfaceBuilderKey = $queryInterfaceBuilderKey;
	}

	/**
	 * @see DependencyBuilder::buildObject
	 *
	 * @param DependencyManager $dependencyManager
	 *
	 * @return QueryInterface
	 */
	public function buildObject( DependencyManager $dependencyManager ) {
		return new MediaWikiQueryInterface(
			$dependencyManager->newObject( $this->queryInterfaceBuilderKey )
		);
	}

}
