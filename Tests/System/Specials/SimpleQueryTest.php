<?php

namespace Tests\Integration\Wikibase\Query\Special;

use Wikibase\Test\PermissionsHelper;
use \Wikibase\Test\SpecialPageTestBase;
use \Wikibase\Query\Specials\SimpleQuery;

/**
 * @group Wikibase
 * @group WikibaseQuery
 * @group SpecialPage
 * @group WikibaseSpecialPage
 * @group medium
 *
 * @since 0.1
 * @licence GNU GPL v2+
 * @author Daniel Werner < daniel.werner@wikimedia.de >
 */
class SimpleQueryTest extends SpecialPageTestBase {

	protected $permissions;
	protected $old_user;

	public function setUp() {
		global $wgGroupPermissions, $wgUser;

		parent::setUp();

		$this->permissions = $wgGroupPermissions;
		$this->old_user = $wgUser;
	}

	public function tearDown() {
		global $wgGroupPermissions;
		global $wgUser;

		$wgGroupPermissions = $this->permissions;

		if ( $this->old_user ) { // should not be null, but sometimes, it is
			$wgUser = $this->old_user;
		}

		if ( $wgUser ) { // should not be null, but sometimes, it is
			// reset rights cache
			$wgUser->addGroup( "dummy" );
			$wgUser->removeGroup( "dummy" );
		}

		parent::tearDown();
	}

	protected function newSpecialPage() {
		return new SimpleQuery();
	}

	public function testExecute() {
		$matchers['property'] = array(
			'tag' => 'input',
			'attributes' => array(
				'id' => 'wb-specialsimplequery-property',
				'name' => 'property',
			)
		);
		$matchers['valuejson'] = array(
			'tag' => 'input',
			'attributes' => array(
				'id' => 'wb-specialsimplequery-valuejson',
				'name' => 'valuejson',
			)
		);
		$matchers['submit'] = array(
			'tag' => 'input',
			'attributes' => array(
				'id' => 'wikibase-specialsimplequery-submit',
				'class' => 'wb-input-button',
				'type' => 'submit',
			)
		);

		list( $output, ) = $this->executeSpecialPage( '' );
		foreach( $matchers as $key => $matcher ) {
			$this->assertTag( $matcher, $output, "Failed to match html output '$key'" );
		}
	}

	public function testExecuteWithoutPermissions() {
		$this->setExpectedException( 'PermissionsError' );
		PermissionsHelper::applyPermissions( array( // permissions
			'*'    => array( 'wikibase-query-run' => false ),
			'user' => array( 'wikibas-query-run' => false )
		) );

		$this->executeSpecialPage( '' );
	}

}
