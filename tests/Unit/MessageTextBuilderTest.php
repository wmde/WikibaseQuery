<?php

namespace Tests\Unit\Wikibase\Query;

use PHPUnit_Framework_MockObject_Matcher_Parameters;
use Wikibase\Query\MessageTextBuilder;

/**
 * @covers Wikibase\Query\MessageTextBuilder
 *
 * @group WikibaseQuery
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class MessageTextBuilderTest extends \PHPUnit_Framework_TestCase {

	public function messageArgumentsProvider() {
		return array(
			array(
				'some message text',
				array(
					'msgKey'
				),
			),
			array(
				'in your message',
				array(
					'msgKey',
					'foo',
				),
			),
			array(
				'',
				array(
					'foo',
					'bar',
					'baz',
				),
			),
		);
	}

	private $messageText;
	private $someMessageArguments;

	/**
	 * @dataProvider messageArgumentsProvider
	 */
	public function testCallsMsgOnContext( $messageText, array $someMessageArguments ) {
		$this->messageText = $messageText;
		$this->someMessageArguments = $someMessageArguments;

		$messageBuilder = new MessageTextBuilder( $this->newMockMessageBuilder() );

		$actualMessage = call_user_func_array(
			array(
				$messageBuilder,
				'msgText'
			),
			$someMessageArguments
		);

		$this->assertEquals( $this->messageText, $actualMessage );
	}

	private function newMockMessage() {
		$message = $this->getMockBuilder( 'Message' )
			->disableOriginalConstructor()->getMock();

		$message->expects( $this->once() )
			->method( 'text' )
			->will( $this->returnValue( $this->messageText ) );

		return $message;
	}

	private function newMockMessageBuilder() {
		$context = $this->getMockBuilder( 'Wikibase\Query\MessageBuilder' )
			->disableOriginalConstructor()->getMock();

		$invocationMocker = $context->expects( $this->once() )
			->method( 'msg' )
			->will( $this->returnValue( $this->newMockMessage() ) );

		// This is somewhat of a hack, pending a better approach.
		// http://stackoverflow.com/questions/20078008/expecting-a-variable-list-of-arguments-in-phpunit
		$invocationMocker->getMatcher()->parametersMatcher
			= new PHPUnit_Framework_MockObject_Matcher_Parameters( $this->someMessageArguments );

		return $context;
	}

}
