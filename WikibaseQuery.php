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
	// Do not initialize more then once.
	return;
}

define( 'WIKIBASE_QUERY_VERSION', '0.1 alpha' );

if ( version_compare( $GLOBALS['wgVersion'], '1.20c', '<' ) ) {
	throw new Exception( 'Wikibase requires MediaWiki 1.20 or above.' );
}

if ( is_readable( __DIR__ . '/vendor/autoload.php' ) ) {
	include_once( __DIR__ . '/vendor/autoload.php' );
}

if ( !defined( 'WB_VERSION' ) && is_readable( __DIR__ . '/../Wikibase/repo/Wikibase.php' ) ) {
	include_once( __DIR__ . '/../Wikibase/repo/Wikibase.php' );
}

if ( !defined( 'WIKIBASE_QUERYENGINE_VERSION' ) && is_readable( __DIR__ . '/../WikibaseQueryEngine/WikibaseQueryEngine.php' ) ) {
	include_once( __DIR__ . '/../WikibaseQueryEngine/WikibaseQueryEngine.php' );
}

if ( !defined( 'WB_VERSION' ) ) {
	throw new Exception( 'Wikibase Query depends on the Wikibase Repo extension.' );
}

if ( !defined( 'WIKIBASE_QUERYENGINE_VERSION' ) ) {
	throw new Exception( 'Wikibase Query depends on the Wikibase QueryEngine component.' );
}

// @codeCoverageIgnoreStart
spl_autoload_register( function ( $className ) {
	$className = ltrim( $className, '\\' );
	$fileName = '';
	$namespace = '';

	if ( $lastNsPos = strripos( $className, '\\') ) {
		$namespace = substr( $className, 0, $lastNsPos );
		$className = substr( $className, $lastNsPos + 1 );
		$fileName  = str_replace( '\\', '/', $namespace ) . '/';
	}

	$fileName .= str_replace( '_', '/', $className ) . '.php';

	$namespaceSegments = explode( '\\', $namespace );

	if ( $namespaceSegments[0] === 'Wikibase' && count( $namespaceSegments ) > 1 && $namespaceSegments[1] === 'Query' ) {
		if ( count( $namespaceSegments ) === 2 || $namespaceSegments[2] !== 'Tests' ) {
			require_once __DIR__ . '/src/' . $fileName;
		}
	}
} );

call_user_func( function() {
	$setup = new \Wikibase\Query\Setup\ExtensionSetup(
		$GLOBALS,
		__DIR__,
		'Wikibase\Query\DIC\ExtensionAccess::setRegistryBuilder'
	);

	$setup->run();
} );
// @codeCoverageIgnoreEnd
