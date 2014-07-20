<?php

namespace Tests\Unit\Wikibase\Query\DIC;

use Wikibase\Query\DIC\Builders\EntitiesImporterBuilder;
use Wikibase\Query\DIC\WikibaseQueryBuilder;
use Wikibase\Repo\WikibaseRepo;

/**
 * @covers Wikibase\Query\DIC\Builders\EntitiesImporterBuilder
 *
 * @group WikibaseQuery
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class EntitiesImporterBuilderTest extends \PHPUnit_Framework_TestCase {

	public function testConstruction() {
		$importerBuilder = new EntitiesImporterBuilder( WikibaseRepo::getDefaultInstance() );
		$wikibaseQueryBuilder = new WikibaseQueryBuilder( $GLOBALS );

		$importer = $importerBuilder->buildObject( $wikibaseQueryBuilder->buildDependencyManager() );

		$this->assertInstanceOf( 'Wikibase\QueryEngine\Importer\EntitiesImporter', $importer );
	}

}
