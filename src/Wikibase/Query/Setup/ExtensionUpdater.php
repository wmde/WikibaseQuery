<?php

namespace Wikibase\Query\Setup;

use DatabaseUpdater;
use Wikibase\QueryEngine\QueryStoreSetup;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class ExtensionUpdater {

	/**
	 * @var QueryStoreSetup
	 */
	protected $queryStoreSetup;

	/**
	 * @var DatabaseUpdater
	 */
	protected $updater;

	public function __construct( QueryStoreSetup $queryStoreSetup ) {
		$this->queryStoreSetup = $queryStoreSetup;
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
		$this->queryStoreSetup->install();
	}

	protected function updateStore() {
		$this->queryStoreSetup->update();
	}

}
