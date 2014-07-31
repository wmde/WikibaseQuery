<?php

namespace Wikibase\Query\Cli\EntitiesImporter;

use InvalidArgumentException;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class Options {

	private $maxBatchSize = 10;
	private $previousEntityId = null;
	private $limit = null;

	/**
	 * @param int $maxBatchSize
	 * @throws InvalidArgumentException
	 */
	public function setBatchSize( $maxBatchSize ) {
		if ( !is_int( $maxBatchSize ) || $maxBatchSize < 1 ) {
			throw new InvalidArgumentException( '$maxBatchSize needs to be a positive integer' );
		}

		$this->maxBatchSize = $maxBatchSize;
	}

	/**
	 * @param string|null $previousEntityId
	 */
	public function setContinuationId( $previousEntityId ) {
		$this->previousEntityId = $previousEntityId;
	}

	/**
	 * @param int|null $limit
	 * @throws InvalidArgumentException
	 */
	public function setLimit( $limit ) {
		if ( $limit !== null && ( !is_int( $limit ) || $limit < 1 ) ) {
			throw new InvalidArgumentException( '$limit needs to be a positive integer' );
		}

		$this->limit = $limit;
	}

	/**
	 * @return int
	 */
	public function getBatchSize() {
		return $this->maxBatchSize;
	}

	/**
	 * @return int|null
	 */
	public function getLimit() {
		return $this->limit;
	}

	/**
	 * @return string|null
	 */
	public function getPreviousEntityId() {
		return $this->previousEntityId;
	}

}