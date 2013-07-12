<?php

/**
 * MediaWiki setup for the Wikibase Query extension.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @since 0.1
 *
 * @file
 * @ingroup WikibaseQuery
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */

use Wikibase\Query\DIC\Builders\ByPropertyValueEntityFinderBuilder;

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
// @codeCoverageIgnoreEnd

call_user_func( function() {
	global $wgExtensionCredits, $wgExtensionMessagesFiles, $wgHooks, $wgWBRepoSettings;
	global $wgExtraNamespaces, $wgContentHandlers;

	$wgExtensionCredits['wikibase'][] = array(
		'path' => __DIR__,
		'name' => 'Wikibase Query',
		'version' => WIKIBASE_QUERY_VERSION,
		'author' => array(
			'[https://www.mediawiki.org/wiki/User:Jeroen_De_Dauw Jeroen De Dauw]',
		),
		'url' => 'https://www.mediawiki.org/wiki/Extension:Wikibase_Query',
		'descriptionmsg' => 'wikibasequery-desc'
	);

	$wgExtensionMessagesFiles['WikibaseQuery'] = __DIR__ . '/WikibaseQuery.i18n.php';

	/**
	 * Hook to add PHPUnit test cases.
	 * @see https://www.mediawiki.org/wiki/Manual:Hooks/UnitTestsList
	 *
	 * @codeCoverageIgnore
	 *
	 * @since 0.1
	 *
	 * @param array $files
	 *
	 * @return boolean
	 */
	$wgHooks['UnitTestsList'][]	= function( array &$files ) {
		$directoryIterator = new RecursiveDirectoryIterator( __DIR__ . '/Tests/Phpunit/' );

		/**
		 * @var SplFileInfo $fileInfo
		 */
		foreach ( new RecursiveIteratorIterator( $directoryIterator ) as $fileInfo ) {
			if ( substr( $fileInfo->getFilename(), -8 ) === 'Test.php' ) {
				$files[] = $fileInfo->getPathname();
			}
		}

		return true;
	};

	$wgWBRepoSettings['entityPrefixes']['y'] = 'query';

	define( 'CONTENT_MODEL_WIKIBASE_QUERY', "wikibase-query" );

	$wgHooks['FormatAutocomments'][] = array( 'Wikibase\Autocomment::onFormat', array( CONTENT_MODEL_WIKIBASE_QUERY, "wikibase-query" ) );

	$wgContentHandlers[CONTENT_MODEL_WIKIBASE_QUERY] = '\Wikibase\Query\QueryHandler';

	define( 'WB_NS_QUERY', 124 );
	define( 'WB_NS_QUERY_TALK', 125 );

	$wgExtraNamespaces[WB_NS_QUERY] = 'Query';
	$wgExtraNamespaces[WB_NS_QUERY_TALK] = 'Query_talk';

	$wgWBRepoSettings['entityNamespaces'][CONTENT_MODEL_WIKIBASE_QUERY] = WB_NS_QUERY;
} );

\Wikibase\Query\DIC\ExtensionAccess::setRegistryBuilder( function() {
	$dependencyManager = new \Wikibase\Query\DIC\DependencyManager();
	$dependencyManager->registerBuilder( 'byPropertyValueEntityFinder', new ByPropertyValueEntityFinderBuilder() );

	return new \Wikibase\Query\DIC\WikibaseQuery( $dependencyManager );
} );