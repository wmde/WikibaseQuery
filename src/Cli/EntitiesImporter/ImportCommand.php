<?php

namespace Wikibase\Query\Cli\EntitiesImporter;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Wikibase\QueryEngine\Console\Import\CliImportReporter;
use Wikibase\QueryEngine\Importer\EntitiesImporter;

/**
 * @since 0.3
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class ImportCommand extends Command {

	/**
	 * @var ImporterBuilder
	 */
	private $importerBuilder;

	public function setDependencies( ImporterBuilder $importerBuilder ) {
		$this->importerBuilder = $importerBuilder;
	}

	protected function configure() {
		$this->setName( 'import' );
		$this->setDescription( 'Imports a collection of entities into the QueryEngine store' );

		$this->addOption(
			'batchsize',
			'b',
			InputOption::VALUE_OPTIONAL,
			'The number of entities to handle in one go',
			10
		);

		$this->addOption(
			'continue',
			'c',
			InputOption::VALUE_OPTIONAL,
			'The id of the entity to resume from (id not included)'
		);

		$this->addOption(
			'limit',
			'l',
			InputOption::VALUE_OPTIONAL,
			'The maximum number of entities to import'
		);
	}

	protected function execute( InputInterface $input, OutputInterface $output ) {
		$optionsBuilder = new OptionsBuilder( $output );
		$optionsBuilder->setBatchSize( $input->getOption( 'batchsize' ) );
		$optionsBuilder->setLimit( $input->getOption( 'limit' ) );
		$optionsBuilder->setContinuationId( $input->getOption( 'continue' ) );

		$options = $optionsBuilder->getOptions();

		$importer = $this->importerBuilder->newImporter(
			new CliImportReporter( $output ),
			$options
		);

		$this->registerSignalHandlers( $importer, $output );
		$this->reportContinuation( $input, $output );

		$importer->run();
	}

	private function registerSignalHandlers( EntitiesImporter $importer, OutputInterface $output ) {
		if ( function_exists( 'pcntl_signal' ) ) {
			pcntl_signal( SIGINT, array( $importer, 'stop' ) );
			pcntl_signal( SIGTERM, array( $importer, 'stop' ) );
		}
		else {
			$output->writeln( '<comment>PCNTL not available; running without graceful interruption support</comment>' );
		}
	}

	private function reportContinuation( InputInterface $input, OutputInterface $output ) {
		$previousEntityId = $input->getOption( 'continue' );

		if ( $previousEntityId !== null ) {
			$output->writeln( "<info>Continuing from </info><comment>$previousEntityId</comment>" );
		}
	}

}
