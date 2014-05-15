<?php

namespace Tests\System\Wikibase\Query\Special;

use RequestContext;
use Wikibase\Query\Specials\SimpleQuery;
use Wikibase\Test\PermissionsHelper;
use Wikibase\Test\SpecialPageTestBase;

/**
 * @group Wikibase
 * @group WikibaseQuery
 * @group SpecialPage
 * @group WikibaseSpecialPage
 * @group medium
 * @group SimpleQueryTest
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
				'class' => 'mw-htmlform-submit',
				'type' => 'submit',
			)
		);

		list( $output, ) = $this->executeSpecialPage( '' );
		foreach( $matchers as $key => $matcher ) {
			$this->assertTag( $matcher, $output, "Failed to match html output '$key'" );
		}
	}

	/**
	 * FIXME: this test no longer passes using Wikibase.git master
	 */
	public function testExecuteWithoutPermissions() {
		$this->setExpectedException( 'PermissionsError' );
		PermissionsHelper::applyPermissions( array( // permissions
			'*'    => array( 'wikibase-query-run' => false ),
			'user' => array( 'wikibas-query-run' => false )
		) );

		// SpecialPageTestBase uses the main request context for execution of the
		// special page. Since the user attached to that might have cached
		// permissions, we need to clear them for the permission changes to apply.
		RequestContext::getMain()->getUser()->clearInstanceCache();
		$r = $this->executeSpecialPage( '' );
	}
}
