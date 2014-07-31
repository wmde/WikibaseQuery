<?php

namespace Wikibase\Query\Cli;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Wikibase\QueryEngine\QueryEngineException;
use Wikibase\QueryEngine\SQLStore\Setup\Installer;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class SQLStoreInstallCommand extends Command {

	/**
	 * @var Installer
	 */
	private $installer;

	public function setDependencies( Installer $installer ) {
		$this->installer = $installer;
	}

	protected function configure() {
		$this->setName( 'store:install' );
		$this->setDescription( 'Installs the QueryEngine store' );
	}

	protected function execute( InputInterface $input, OutputInterface $output ) {
		$output->write( '<info>Installing the QueryEngine store... </info>' );

		try {
			$this->installer->install();
			$output->writeln( '<info>done.</info>' );
		}
		catch ( QueryEngineException $ex ) {
			$output->writeln( '<error>failed!</error>' );
			$output->writeln( '<error>' . $ex->getMessage() . '</error>' );
		}

	}

}
