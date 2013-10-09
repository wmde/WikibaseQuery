<?php

namespace Wikibase\Query\DIC;

use Wikibase\Query\DIC\Builders\ByPropertyValueEntityFinderBuilder;
use Wikibase\Query\DIC\Builders\ExtensionUpdaterBuilder;
use Wikibase\Query\DIC\Builders\QueryInterfaceBuilder;
use Wikibase\Query\DIC\Builders\QueryStoreBuilder;
use Wikibase\Repo\WikibaseRepo;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class WikibaseQueryBuilder {

	/**
	 * @return WikibaseQuery
	 */
	public function build() {
		$dependencyManager = new DependencyManager();

		$dependencyManager->registerBuilder(
			'byPropertyValueEntityFinder',
			new ByPropertyValueEntityFinderBuilder(
				WikibaseRepo::getDefaultInstance()
			)
		);

		$dependencyManager->registerBuilder(
			'queryStore',
			new QueryStoreBuilder(
				DB_SLAVE,
				$GLOBALS['wgDBtype']
			)
		);

		$dependencyManager->registerBuilder(
			'slaveQueryInterface',
			new QueryInterfaceBuilder(
				DB_SLAVE,
				$GLOBALS['wgDBtype']
			)
		);

		$dependencyManager->registerBuilder(
			'extensionUpdater',
			new ExtensionUpdaterBuilder()
		);

		return new WikibaseQuery( $dependencyManager );
	}

}
