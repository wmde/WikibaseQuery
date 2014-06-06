<?php

namespace Tests\Unit\Wikibase\Query\Setup;

use Wikibase\Query\Setup\ExtensionSetup;

/**
 * @covers Wikibase\Query\Setup\ExtensionSetup
 *
 * @group Wikibase
 * @group WikibaseQuery
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class ExtensionSetupTest extends \PHPUnit_Framework_TestCase {

	protected $globalVars;
	protected $rootDir;
	protected $dicRegistrant;

	public function setUp() {
		parent::setUp();

		$this->globalVars = array(
			'wgHooks' => array(),
			'wgResourceModules' => array(),
			'wgDBprefix' => ''
		);
		
		$this->rootDir = __DIR__ . str_repeat( DIRECTORY_SEPARATOR . '..', 4 );

		$this->dicRegistrant = function() {};
	}

	public function testCallsDicRegistrantCorrectly() {
		$called = false;
		$self = $this;

		$dicRegistrant = function( $wikibaseQueryBuilder ) use ( &$called, $self ) {
			$called = true;
			$self->assertInternalType( 'callable', $wikibaseQueryBuilder );
			$self->assertInstanceOf( 'Wikibase\Query\DIC\WikibaseQuery', call_user_func( $wikibaseQueryBuilder ) );
		};

		$setup = new ExtensionSetup( $this->globalVars, $this->rootDir, $dicRegistrant );
		$setup->run();

		$this->assertTrue( $called, '$dicRegistrant should have been called' );
	}

	public function testHookRegistration() {
		$this->runSetup();
		$this->assertHooksAreRegistered();
	}

	protected function assertHooksAreRegistered() {
		$this->assertNotEmpty( $this->globalVars['wgHooks'] );
	}

	protected function runSetup() {
		$setup = new ExtensionSetup( $this->globalVars, $this->rootDir, $this->dicRegistrant );
		$setup->run();
	}

	public function testInternationalizationRegistration() {
		$this->runSetup();
		$this->assertInternationalizationIsRegistered();
	}

	protected function assertInternationalizationIsRegistered() {
		$this->assertArrayHasKey( 'WikibaseQuery', $this->globalVars['wgExtensionMessagesFiles'] );
		$this->assertArrayHasKey( 'WikibaseQueryAliases', $this->globalVars['wgExtensionMessagesFiles'] );
	}

}
