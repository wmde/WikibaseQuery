<?php

/**
 * MediaWiki setup for the Wikibase Query extension.
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */

if ( !defined( 'MEDIAWIKI' ) ) {
	die( 'Not an entry point.' );
}

if ( defined( 'WIKIBASE_QUERY_VERSION' ) ) {
	// Do not initialize more than once.
	return 1;
}

define( 'WIKIBASE_QUERY_VERSION', '0.1 alpha' );

if ( version_compare( $GLOBALS['wgVersion'], '1.20c', '<' ) ) {
	throw new Exception( 'Wikibase requires MediaWiki 1.20 or above.' );
}

if ( is_readable( __DIR__ . '/vendor/autoload.php' ) ) {
	include_once( __DIR__ . '/vendor/autoload.php' );
}

call_user_func( function() {
	$setup = new \Wikibase\Query\Setup\ExtensionSetup(
		$GLOBALS,
		__DIR__,
		'Wikibase\Query\DIC\ExtensionAccess::setRegistryBuilder'
	);

	$setup->run();
} );

