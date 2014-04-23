<?php

/**
 * PHPUnit test bootstrap file for the Wikibase Query extension.
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */

if ( php_sapi_name() !== 'cli' ) {
	die( 'Not an entry point' );
}

require_once( __DIR__ . '/evilMediaWikiBootstrap.php' );

require_once( __DIR__ . '/bootstrap.php' );
