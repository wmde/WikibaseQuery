<?php

namespace Wikibase\Query\DIC;

use Wikibase\Query\DIC\Builders\ByPropertyValueEntityFinderBuilder;
use Wikibase\Query\DIC\Builders\DatabaseConnectionBuilder;
use Wikibase\Query\DIC\Builders\ExtensionUpdaterBuilder;
use Wikibase\Query\DIC\Builders\QueryInterfaceBuilder;
use Wikibase\Query\DIC\Builders\QueryStoreWithDependenciesBuilder;
use Wikibase\Query\DIC\Builders\SQLStoreBuilder;
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

		if ( defined( 'MW_PHPUNIT_TEST' ) ) {
			$dependencyManager->registerBuilder(
				'sqlStore',
				new SQLStoreBuilder(
					'WikibaseQuery test store',
					'test_wbq_'
				)
			);
		}
		else {
			$dependencyManager->registerBuilder(
				'sqlStore',
				new SQLStoreBuilder(
					'WikibaseQuery SQLStore 0.1 alpha',
					'wbq_'
				)
			);
		}

		$dependencyManager->registerBuilder(
			'extensionUpdater',
			new ExtensionUpdaterBuilder()
		);

		$dependencyManager->registerBuilder(
			'queryStoreWithDependencies',
			new QueryStoreWithDependenciesBuilder()
		);

		$dependencyManager->registerBuilder(
			'slaveQueryInterface',
			new QueryInterfaceBuilder(
				'slaveConnectionProvider'
			)
		);

		$dependencyManager->registerBuilder(
			'masterQueryInterface',
			new QueryInterfaceBuilder(
				'masterConnectionProvider'
			)
		);

		$dependencyManager->registerBuilder(
			'slaveConnectionProvider',
			new DatabaseConnectionBuilder(
				DB_SLAVE
			)
		);

		$dependencyManager->registerBuilder(
			'masterConnectionProvider',
			new DatabaseConnectionBuilder(
				DB_MASTER
			)
		);

		return new WikibaseQuery( $dependencyManager );
	}

}
