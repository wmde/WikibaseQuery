<?php

namespace Wikibase\Query\Cli\EntitiesImporter;

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
class ImporterBuilder {

	private $storeWriter;
	private $epp;
	private $entityLookup;
	private $idParser;

	public function __construct( QueryStoreWriter $storeWriter, EntityPerPage $epp, EntityLookup $entityLookup, EntityIdParser $idParser ) {
		$this->storeWriter = $storeWriter;
		$this->epp = $epp;
		$this->entityLookup = $entityLookup;
		$this->idParser = $idParser;
	}

	public function newImporter( ImportReporter $reporter, Options $options ) {
		return new EntitiesImporter(
			$this->storeWriter,
			$this->newEntityIterator( $options ),
			$reporter
		);
	}

	private function newEntityIterator( Options $options ) {
		$idFetcherBuilder = new BatchingEntityIdFetcherBuilder(
			$this->epp,
			$this->getPreviousId( $options )
		);

		$iterator = new BatchingIterator( new BatchingEntityFetcher(
			$idFetcherBuilder->getFetcher(),
			$this->entityLookup
		) );

		$iterator->setMaxBatchSize( $options->getBatchSize() );

		// TODO: hold into account the limit option
		return $iterator;
	}

	private function getPreviousId( Options $options ) {
		try {
			return $this->idParser->parse( $options->getPreviousEntityId() );
		}
		catch ( EntityIdParsingException $ex ) {
			return null;
		}
	}

}