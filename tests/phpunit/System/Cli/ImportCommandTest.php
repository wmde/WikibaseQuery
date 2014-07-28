<?php

namespace Tests\System\Wikibase\Query\Cli;

use DataValues\StringValue;
use Symfony\Component\Console\Tester\CommandTester;
use Wikibase\DataModel\Claim\Statement;
use Wikibase\DataModel\Entity\Entity;
use Wikibase\DataModel\Entity\Item;
use Wikibase\DataModel\Snak\PropertyValueSnak;
use Wikibase\Query\DIC\Builders\EntitiesImporterBuilder;
use Wikibase\Query\DIC\ExtensionAccess;
use Wikibase\QueryEngine\Console\Import\ImportEntitiesCommand;
use Wikibase\Repo\WikibaseRepo;

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

	public function setUp() {
		parent::setUp();

		$item = Item::newEmpty();
		$item->setId( 133742 );
		$this->saveEntity( $item );

		$item = Item::newEmpty();
		$item->setId( 133723 );
		$statement = new Statement( new PropertyValueSnak( 42, new StringValue( 'foo' ) ) );
		$statement->setGuid( 'kittens' );
		$item->addClaim( $statement );
		$this->saveEntity( $item );
	}

	private function saveEntity( Entity $entity ) {
		$store = WikibaseRepo::getDefaultInstance()->getStore()->getEntityStore();
		$store->saveEntity( $entity, __METHOD__, $GLOBALS['wgUser'] );
	}

	public function testImportCommand() {
		$command = new ImportEntitiesCommand();

		$repo = WikibaseRepo::getDefaultInstance();

		$command->setDependencies( new EntitiesImporterBuilder(
			ExtensionAccess::getWikibaseQuery()->getQueryStoreWriter(),
			$repo->getStore()->newEntityPerPage(),
			$repo->getEntityLookup(),
			$repo->getEntityIdParser()
		) );

		$tester = new CommandTester( $command );
		$tester->execute( array() );

		$this->assertContains( 'Importing Q133723', $tester->getDisplay() );
	}

}
