<?php

namespace Tests\Integration\Wikibase\Query;

use Wikibase\Query\DIC\ExtensionAccess;

/**
 * @group WikibaseQuery
 * @group WikibaseQueryIntegration
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class ObjectConstructionTest extends \PHPUnit_Framework_TestCase {

	public function testConstructByPropertyValueEntityFinder() {
		$byPropertyValueEntityFinder = ExtensionAccess::getWikibaseQuery()->getByPropertyValueEntityFinder();
		$this->assertInstanceOf( 'Wikibase\Query\ByPropertyValueEntityFinder', $byPropertyValueEntityFinder );
	}

	public function testConstructExtensionUpdater() {
		$updater = ExtensionAccess::getWikibaseQuery()->getExtensionUpdater();
		$this->assertInstanceOf( 'Wikibase\Query\Setup\ExtensionUpdater', $updater );
	}

	public function testConstructStoreWriter() {
		$updater = ExtensionAccess::getWikibaseQuery()->getQueryStoreWriter();
		$this->assertInstanceOf( 'Wikibase\QueryEngine\QueryStoreWriter', $updater );
	}

}
