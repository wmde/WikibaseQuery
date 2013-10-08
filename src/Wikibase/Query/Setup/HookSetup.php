<?php

namespace Wikibase\Query\Setup;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;
use Wikibase\Query\DIC\ExtensionAccess;

/**
 * @since 0.1
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class HookSetup {

	/**
	 * @param array $hooks Same format as $wgHooks
	 * @param string $rootDirectory
	 */
	public function __construct( array &$hooks, $rootDirectory ) {
		$this->hooks =& $hooks;
		$this->rootDirectory = $rootDirectory;
	}

	public function run() {
		$this->registerUnitTests();
		$this->registerExtensionSchemaUpdates();
	}

	protected function registerUnitTests() {
		$rootDir = $this->rootDirectory;

		// https://www.mediawiki.org/wiki/Manual:Hooks/UnitTestsList
		$this->hooks['UnitTestsList'][]	= function( array &$files ) use ( $rootDir ) {
			$directoryIterator = new RecursiveDirectoryIterator( $rootDir . '/Tests/' );

			/**
			 * @var SplFileInfo $fileInfo
			 */
			foreach ( new RecursiveIteratorIterator( $directoryIterator ) as $fileInfo ) {
				if ( substr( $fileInfo->getFilename(), -8 ) === 'Test.php' ) {
					$files[] = $fileInfo->getPathname();
				}
			}

			return true;
		};
	}

	protected function registerExtensionSchemaUpdates() {
		// https://www.mediawiki.org/wiki/Manual:Hooks/LoadExtensionSchemaUpdates
		$this->hooks['LoadExtensionSchemaUpdates'][] = function( \DatabaseUpdater $updater ) {
			// TODO: ExtensionAccess::getWikibaseQuery()->
		};
	}

}