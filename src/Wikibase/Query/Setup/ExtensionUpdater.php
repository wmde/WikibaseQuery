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

	protected $queryStoreInstaller;
	protected $queryStoreUpdater;

	/**
	 * @var DatabaseUpdater
	 */
	protected $updater;

	public function __construct( QueryStoreInstaller $installer, QueryStoreUpdater $updater ) {
		$this->queryStoreInstaller = $installer;
		$this->queryStoreUpdater = $updater;
	}

	public function run( DatabaseUpdater $updater ) {
		$this->updater = $updater;

		$this->applyUpdateIfNotAlreadyDone( 'installStore' );

		$this->updateStore();
	}

	protected function applyUpdateIfNotAlreadyDone( $functionName ) {
		$updateName = 'wbquery-' . $functionName;

		if ( !$this->updater->updateRowExists( $updateName ) ) {
			call_user_func( array( $this, $functionName ) );
			$this->updater->insertUpdateRow( $updateName );
		}
	}

	protected function installStore() {
		$this->queryStoreInstaller->install();
	}

	protected function updateStore() {
		$this->queryStoreUpdater->update();
	}

	protected function reportMessage( $message ) {
		$this->updater->output( $message );
	}

}
