<?php

namespace Wikibase\Query;

use Message;
use RuntimeException;

/**
 * Adapter for the MediaWiki message localization code.
 *
 * @since 0.1
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class MessageTextBuilder {

	private $messageBuilder;

	public function __construct( MessageBuilder $builder ) {
		$this->messageBuilder = $builder;
	}

	/**
	 * @param string $messageKey
	 * @return string
	 * @throws RuntimeException
	 */
	public function msgText( $messageKey /* message arguments */ ) {
		$message = call_user_func_array( array( $this->messageBuilder, 'msg' ), func_get_args() );

		if ( $message instanceof Message ) {
			return $message->text();
		}

		throw new RuntimeException( 'The returned $message was not of type Message' );
	}

}
