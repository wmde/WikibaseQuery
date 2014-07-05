<?php

namespace Wikibase\Query\Cli;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Wikibase\QueryEngine\QueryEngineException;
use Wikibase\QueryEngine\SQLStore\Setup\Uninstaller;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class SQLStoreUninstallCommand extends Command {

	/**
	 * @var Uninstaller
	 */
	private $uninstaller;

	public function setDependencies( Uninstaller $uninstaller ) {
		$this->uninstaller = $uninstaller;
	}

	protected function configure() {
		$this->setName( 'sqlstore:uninstall' );
		$this->setDescription( 'Uninstalls the QueryEngine SQLStore' );
	}

	protected function execute( InputInterface $input, OutputInterface $output ) {
		$output->write( '<info>Uninstalling the QueryEngine SQLStore... </info>' );

		try {
			$this->uninstaller->uninstall();
			$output->writeln( '<info>done.</info>' );
		}
		catch ( QueryEngineException $ex ) {
			$output->writeln( '<error>failed!</error>' );
			$output->writeln( '<error>' . $ex->getMessage() . '</error>' );
		}

	}

}