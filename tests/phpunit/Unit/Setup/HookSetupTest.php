<?php

namespace Tests\Unit\Wikibase\Query\Setup;

use Wikibase\Query\Setup\HookSetup;

/**
 * @covers Wikibase\Query\Setup\HookSetup
 *
 * @group Wikibase
 * @group WikibaseQuery
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class HookSetupTest extends \PHPUnit_Framework_TestCase {

	protected $expectedHooks = array(
		'UnitTestsList',
		'LoadExtensionSchemaUpdates',
		'WikibaseEntityModificationUpdate',
		'WikibaseEntityDeletionUpdate',
	);

	public function testRegistersHooks() {
		$hooks = array();

		$setup = new HookSetup( $hooks, __DIR__ . '/../../..' );
		$setup->run();

		$this->assertHooksAreRegistered( $hooks );
	}

	protected function assertHooksAreRegistered( array $hooks ) {
		$this->assertSameSize( $this->expectedHooks, $hooks );

		foreach ( $this->expectedHooks as $expectedHook ) {
			$this->assertHookIsRegistered( $expectedHook, $hooks );
		}
	}

	protected function assertHookIsRegistered( $hookName, array $actualHooks ) {
		$this->assertArrayHasKey( $hookName, $actualHooks );
		$this->assertCount( 1, $actualHooks[$hookName] );
		$this->assertInternalType( 'callable', reset( $actualHooks[$hookName] ) );
	}

}
