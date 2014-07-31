<?php

namespace Wikibase\Query\Cli\EntitiesImporter;

use InvalidArgumentException;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class OptionsBuilder {

	/**
	 * @var OutputInterface
	 */
	private $output;

	/**
	 * @var Options
	 */
	private $options;

	public function __construct( OutputInterface $output ) {
		$this->output = $output;
		$this->options = new Options();
	}

	private function reportError( $message ) {
		$this->output->writeln( $message );
	}

	public function setBatchSize( $maxBatchSize ) {
		if ( (int)$maxBatchSize < 0 ) {
			$this->reportError( 'The max batch size should be a positive integer' );
		}

		$this->options->setBatchSize( (int)$maxBatchSize );
	}

	/**
	 * @param string|null $previousEntityId
	 */
	public function setContinuationId( $previousEntityId ) {
		$this->options->setContinuationId( $previousEntityId );
	}

	/**
	 * @param int|null $limit
	 * @throws InvalidArgumentException
	 */
	public function setLimit( $limit ) {
		if ( $limit !== null && (int)$limit < 0 ) {
			$this->reportError( 'The limit should be a positive integer' );
		}

		$this->options->setLimit( $limit === null ? null : (int)$limit );
	}

	/**
	 * @return Options
	 */
	public function getOptions() {
		return $this->options;
	}

}