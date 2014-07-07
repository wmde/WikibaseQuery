<?php

namespace Wikibase\Query\Setup;

use DatabaseUpdater;
use Wikibase\QueryEngine\QueryStoreInstaller;
use Wikibase\QueryEngine\QueryStoreUpdater;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class ExtensionUpdater {

	private $queryStoreInstaller;
	private $queryStoreUpdater;

	/**
	 * @var DatabaseUpdater
	 */
	private $updater;

	public function __construct( QueryStoreInstaller $installer, QueryStoreUpdater $updater ) {
		$this->queryStoreInstaller = $installer;
		$this->queryStoreUpdater = $updater;
	}

	public function run( DatabaseUpdater $updater ) {
		$this->updater = $updater;

		$this->applyUpdateIfNotAlreadyDone( 'installStore' );

		$this->updateStore();
	}

	private function applyUpdateIfNotAlreadyDone( $functionName ) {
		$updateName = 'wbquery-' . $functionName;

		if ( !$this->updater->updateRowExists( $updateName ) ) {
			call_user_func( array( $this, $functionName ) );
			$this->updater->insertUpdateRow( $updateName );
		}
	}

	private function installStore() {
		$this->queryStoreInstaller->install();
	}

	private function updateStore() {
		$this->queryStoreUpdater->update();
	}

	private function reportMessage( $message ) {
		$this->updater->output( $message );
	}

}
