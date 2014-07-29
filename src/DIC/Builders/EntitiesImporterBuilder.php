<?php

namespace Wikibase\Query\DIC\Builders;

use BatchingIterator\BatchingIterator;
use Wikibase\DataModel\Entity\EntityIdParser;
use Wikibase\DataModel\Entity\EntityIdParsingException;
use Wikibase\EntityPerPage;
use Wikibase\EntityStore\BatchingEntityFetcher;
use Wikibase\EntityStore\BatchingEntityIdFetcherBuilder;
use Wikibase\Lib\Store\EntityLookup;
use Wikibase\QueryEngine\Importer\EntitiesImporter;
use Wikibase\QueryEngine\Importer\ImportReporter;
use Wikibase\QueryEngine\QueryStoreWriter;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class EntitiesImporterBuilder implements \Wikibase\QueryEngine\Importer\EntitiesImporterBuilder {

	private $storeWriter;
	private $epp;
	private $entityLookup;
	private $idParser;

	private $maxBatchSize = 10;
	private $reporter = null;
	private $previousEntityId = null;

	public function __construct( QueryStoreWriter $storeWriter, EntityPerPage $epp, EntityLookup $entityLookup, EntityIdParser $idParser ) {
		$this->storeWriter = $storeWriter;
		$this->epp = $epp;
		$this->entityLookup = $entityLookup;
		$this->idParser = $idParser;
	}

	/**
	 * @param int $maxBatchSize
	 */
	public function setBatchSize( $maxBatchSize ) {
		$this->maxBatchSize = $maxBatchSize;
	}

	/**
	 * @param $reporter ImportReporter
	 */
	public function setReporter( ImportReporter $reporter ) {
		$this->reporter = $reporter;
	}

	/**
	 * @param string $previousEntityId
	 */
	public function setContinuationId( $previousEntityId ) {
		$this->previousEntityId = $previousEntityId;
	}

	/**
	 * @return EntitiesImporter
	 */
	public function newImporter() {
		return new EntitiesImporter(
			$this->storeWriter,
			$this->newEntityIterator(),
			$this->reporter
		);
	}

	private function newEntityIterator() {
		$idFetcherBuilder = new BatchingEntityIdFetcherBuilder( $this->epp, $this->getPreviousId() );

		$iterator = new BatchingIterator( new BatchingEntityFetcher(
			$idFetcherBuilder->getFetcher(),
			$this->entityLookup
		) );

		$iterator->setMaxBatchSize( $this->maxBatchSize );

		return $iterator;
	}

	private function getPreviousId() {
		try {
			return $this->idParser->parse( $this->previousEntityId );
		}
		catch ( EntityIdParsingException $ex ) {
			return null;
		}
	}

}