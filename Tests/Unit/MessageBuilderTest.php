<?php

namespace Tests\Unit\Wikibase\Query;

use PHPUnit_Framework_MockObject_Matcher_Parameters;
use Wikibase\Query\MessageBuilder;

/**
 * @covers Wikibase\Query\MessageBuilder
 *
 * @group WikibaseQuery
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class MessageBuilderTest extends \PHPUnit_Framework_TestCase {

	public function msgProvider() {
		return array(
			array(
				MessageBuilder::CONTENT_LANGUAGE,
				array(),
			),
			array(
				MessageBuilder::CONTENT_LANGUAGE,
				array(
					'foo',
					false,
					array( 'bar' )
				),
			),
			array(
				MessageBuilder::INTERFACE_LANGUAGE,
				array(
					'foo'
				),
			),
		);
	}

	private $someMessageArguments;
	private $languageType;

	/**
	 * @dataProvider msgProvider
	 */
	public function testCallsMsgOnContext( $languageType, array $someMessageArguments ) {
		$this->someMessageArguments = $someMessageArguments;
		$this->languageType = $languageType;

		$message = $this->newMockMessage();

		$actualMessage = call_user_func_array(
			array(
				$this->newMessageBuilder( $message ),
				'msg'
			),
			$someMessageArguments
		);

		$this->assertEquals( $message, $actualMessage );
	}

	protected function newMessageBuilder( $message ) {
		$messageBuilder = new MessageBuilder(
			$this->newMockContext( $message, $this->someMessageArguments ),
			$this->languageType
		);

		return $messageBuilder;
	}

	protected function newMockMessage() {
		$message = $this->getMockBuilder( 'Message' )->disableOriginalConstructor()->getMock();

		$message->expects( $this->once() )
			->method( 'setInterfaceMessageFlag' )
			->with( $this->equalTo( $this->languageType ) );

		return $message;
	}

	protected function newMockContext( $message ) {
		$context = $this->getMock( 'IContextSource' );

		$invocationMocker = $context->expects( $this->once() )
			->method( 'msg' )
			->will( $this->returnValue( $message ) );

		// This is somewhat of a hack, pending a better approach.
		// http://stackoverflow.com/questions/20078008/expecting-a-variable-list-of-arguments-in-phpunit
		$invocationMocker->getMatcher()->parametersMatcher
			= new PHPUnit_Framework_MockObject_Matcher_Parameters( $this->someMessageArguments );

		return $context;
	}

}
