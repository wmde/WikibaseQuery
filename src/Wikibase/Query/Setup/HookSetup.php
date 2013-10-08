<?php

namespace Wikibase\Query\Setup;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;
use Wikibase\Query\DIC\ExtensionAccess;

class HookSetup {

	/**
	 * @param array $hooks
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

		/**
		 * Hook to add PHPUnit test cases.
		 * @see https://www.mediawiki.org/wiki/Manual:Hooks/UnitTestsList
		 *
		 * @codeCoverageIgnore
		 *
		 * @since 0.1
		 *
		 * @param array $files
		 *
		 * @return boolean
		 */
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
		$this->hooks['LoadExtensionSchemaUpdates'][] = function( \DatabaseUpdater $updater ) {
			// TODO: ExtensionAccess::getWikibaseQuery()->
		};
	}

}