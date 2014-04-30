<?php

/**
 * Autoload entry point for Wikibase Query that allows enabling
 * of the extension based on configuration.
 *
 * @licence GNU GPL v2+
 */

if ( !array_key_exists( 'wgEnableWikibaseQuery', $GLOBALS ) || $GLOBALS['wgEnableWikibaseQuery'] ) {
	require_once __DIR__ . '/WikibaseQuery.php';
}
