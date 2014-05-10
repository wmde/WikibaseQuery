<?php

namespace Wikibase\Query\DIC;

use Wikibase\Query\DIC\Builders\ByPropertyValueEntityFinderBuilder;
use Wikibase\Query\DIC\Builders\DatabaseConnectionBuilder;
use Wikibase\Query\DIC\Builders\ExtensionUpdaterBuilder;
use Wikibase\Query\DIC\Builders\QueryStoreWithDependenciesBuilder;
use Wikibase\Query\DIC\Builders\QueryStoreWriterBuilder;
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
			new ByPropertyValueEntityFinderBuilder( $this->getRepoFactory() )
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
			new QueryStoreWithDependenciesBuilder( $this->getRepoFactory() )
		);

		$dependencyManager->registerBuilder(
			'connection',
			new DatabaseConnectionBuilder(
				$GLOBALS
			)
		);

		$dependencyManager->registerBuilder(
			'queryStoreWriter',
			new QueryStoreWriterBuilder()
		);

		return new WikibaseQuery( $dependencyManager );
	}

	private function getRepoFactory() {
		return WikibaseRepo::getDefaultInstance();
	}

}
