<?php

namespace Tests\Integration\Wikibase\Query\Special;

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

}