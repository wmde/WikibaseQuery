<?php

namespace Tests\System\Wikibase\Query\Cli;

use DataValues\StringValue;
use Symfony\Component\Console\Tester\CommandTester;
use Wikibase\DataModel\Claim\Statement;
use Wikibase\DataModel\Entity\Item;
use Wikibase\DataModel\Snak\PropertyValueSnak;
use Wikibase\Query\DIC\ExtensionAccess;
use Wikibase\QueryEngine\Console\Import\ImportEntitiesCommand;
use Wikibase\QueryEngine\Importer\EntitiesImporter;

/**
 * @group WikibaseQuery
 * @group WikibaseQuerySystem
 * @group Database
 * @group medium
 * @group Api
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class ImportCommandTest extends \MediaWikiTestCase {

	public function testImportCommand() {
		$command = new ImportEntitiesCommand();

		$command->setDependencies( new EntitiesImporter(
			ExtensionAccess::getWikibaseQuery()->getQueryStoreWriter(),
			new \ArrayIterator( $this->getEntitiesToImport() )
		) );

		$tester = new CommandTester( $command );
		$tester->execute( array() );

		$this->assertContains( 'Importing Q133723', $tester->getDisplay() );
	}

	private function getEntitiesToImport() {
		$entities = array();

		$item = Item::newEmpty();
		$item->setId( 133742 );
		$entities[] = $item;

		$item = Item::newEmpty();
		$item->setId( 133723 );
		$statement = new Statement( new PropertyValueSnak( 42, new StringValue( 'foo' ) ) );
		$statement->setGuid( 'kittens' );
		$item->addClaim( $statement );
		$entities[] = $item;

		return $entities;
	}

}
