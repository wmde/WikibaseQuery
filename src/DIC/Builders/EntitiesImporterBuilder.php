<?php

namespace Wikibase\Query\DIC\Builders;

use BatchingIterator\BatchingIterator;
use Wikibase\EntityStore\BatchingEntityFetcher;
use Wikibase\EntityStore\BatchingEntityIdFetcher;
use Wikibase\EntityStore\BatchingEntityIdFetcherBuilder;
use Wikibase\Query\DIC\DependencyBuilder;
use Wikibase\Query\DIC\DependencyManager;
use Wikibase\QueryEngine\Importer\EntitiesImporter;
use Wikibase\QueryEngine\QueryStoreWriter;
use Wikibase\Repo\WikibaseRepo;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class EntitiesImporterBuilder extends DependencyBuilder {

	private $repo;

	public function __construct( WikibaseRepo $repo ) {
		$this->repo = $repo;
	}

	/**
	 * @see DependencyBuilder::buildObject
	 *
	 * @param DependencyManager $dependencyManager
	 *
	 * @return EntitiesImporter
	 */
	public function buildObject( DependencyManager $dependencyManager ) {
		/**
		 * @var QueryStoreWriter
		 */
		$storeWriter = $dependencyManager->newObject( 'queryStoreWriter' );

		return new EntitiesImporter(
			$storeWriter,
			$this->newEntityIterator()
		);
	}

	private function newEntityIterator() {
		$idFetcherBuilder = new BatchingEntityIdFetcherBuilder(
			$this->repo->getStore()->newEntityPerPage()
		);

		$iterator = new BatchingIterator( new BatchingEntityFetcher(
			$idFetcherBuilder->getFetcher(),
			$this->repo->getEntityLookup()
		) );

		// TODO: update the interface of the command so batch size and start position can be set
		$iterator->setMaxBatchSize( 10 );

		return $iterator;
	}

}
