<?php

namespace Tests\System\Wikibase\Query\Cli;

use DataValues\StringValue;
use Symfony\Component\Console\Tester\CommandTester;
use Wikibase\DataModel\Claim\Claim;
use Wikibase\DataModel\Entity\Entity;
use Wikibase\DataModel\Entity\Item;
use Wikibase\DataModel\Snak\PropertyValueSnak;
use Wikibase\DataModel\Statement\Statement;
use Wikibase\Query\Cli\EntitiesImporter\ImportCommand;
use Wikibase\Query\Cli\EntitiesImporter\ImporterBuilder;
use Wikibase\Query\DIC\ExtensionAccess;
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
		$item->setId( 133723 );
		$this->saveEntity( $item );

		$item = Item::newEmpty();
		$item->setId( 133742 );
		$item->getStatements()->addNewStatement(
			new PropertyValueSnak( 42, new StringValue( 'foo' ) ),
			null,
			null,
			'kittens'
		);
		$this->saveEntity( $item );
	}

	private function saveEntity( Entity $entity ) {
		$store = WikibaseRepo::getDefaultInstance()->getStore()->getEntityStore();
		$store->saveEntity( $entity, __METHOD__, $GLOBALS['wgUser'] );
	}

	public function testImportCommandWithDefaultArguments() {
		$output = $this->getOutputForArguments( array() );

		$this->assertContains( 'Importing Q133723', $output );
		$this->assertContains( 'Importing Q133742', $output );
	}

	private function getOutputForArguments( array $arguments ) {
		$command = new ImportCommand();

		$repo = WikibaseRepo::getDefaultInstance();

		$command->setDependencies( new ImporterBuilder(
			ExtensionAccess::getWikibaseQuery()->getQueryStoreWriter(),
			$repo->getStore()->newEntityPerPage(),
			$repo->getEntityLookup(),
			$repo->getEntityIdParser()
		) );

		$tester = new CommandTester( $command );
		$tester->execute( $arguments );

		return $tester->getDisplay();
	}

	public function testImportCommandWithLimitArgument() {
		$output = $this->getOutputForArguments( array( '--limit' => 1 ) );

		$this->assertNotContains( 'Importing Q133742', $output );
	}

}
