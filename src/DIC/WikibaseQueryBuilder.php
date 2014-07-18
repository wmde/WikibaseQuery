<?php

namespace Wikibase\Query\DIC;

use Wikibase\Query\DIC\Builders\ByPropertyValueEntityFinderBuilder;
use Wikibase\Query\DIC\Builders\CliApplicationBuilder;
use Wikibase\Query\DIC\Builders\DatabaseConnectionBuilder;
use Wikibase\Query\DIC\Builders\EntitiesImporterBuilder;
use Wikibase\Query\DIC\Builders\ExtensionUpdaterBuilder;
use Wikibase\Query\DIC\Builders\QueryStoreWithDependenciesBuilder;
use Wikibase\Query\DIC\Builders\QueryStoreWriterBuilder;
use Wikibase\Query\DIC\Builders\SQLStoreBuilder;
use Wikibase\Query\DIC\Builders\SQLStoreSchemaBuilder;
use Wikibase\Query\DIC\Builders\SQLStoreWriterBuilder;
use Wikibase\Repo\WikibaseRepo;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class WikibaseQueryBuilder {

	private $globalVars;

	public function __construct( array $globalVars ) {
		$this->globalVars = $globalVars;
	}

	/**
	 * @return WikibaseQuery
	 */
	public function build() {
		return new WikibaseQuery( $this->buildDependencyManager() );
	}

	public function buildDependencyManager() {
		$dependencyManager = new DependencyManager();

		$dependencyManager->registerBuilder(
			'byPropertyValueEntityFinder',
			new ByPropertyValueEntityFinderBuilder( $this->getRepoFactory() )
		);

		if ( defined( 'MW_PHPUNIT_TEST' ) ) {
			$dependencyManager->registerBuilder(
				'sqlStore',
				new SQLStoreBuilder(
					'WikibaseQuery test store'
				)
			);
		}
		else {
			$dependencyManager->registerBuilder(
				'sqlStore',
				new SQLStoreBuilder(
					'WikibaseQuery SQLStore 0.1 alpha'
				)
			);
		}

		$dependencyManager->registerBuilder(
			'sqlStoreSchema',
			new SQLStoreSchemaBuilder(
				$this->globalVars['wgDBprefix'] . 'wbq_'
			)
		);

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
				$this->globalVars
			)
		);

		$dependencyManager->registerBuilder(
			'queryStoreWriter',
			new QueryStoreWriterBuilder()
		);

		$dependencyManager->registerBuilder(
			'cliApplication',
			new CliApplicationBuilder()
		);

		$dependencyManager->registerBuilder(
			'entitiesImporter',
			new EntitiesImporterBuilder( $this->getRepoFactory() )
		);

		return $dependencyManager;
	}

	private function getRepoFactory() {
		return WikibaseRepo::getDefaultInstance();
	}

}
