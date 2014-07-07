<?php

namespace Wikibase\Query\Setup;

use Wikibase\Query\DIC\ExtensionAccess;
use Wikibase\Query\DIC\WikibaseQueryBuilder;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class ExtensionSetup {

	private $globalVars;
	private $rootDirectory;
	private $dicRegistrant;

	/**
	 * @param array $globalVars
	 * @param string $rootDirectory
	 * @param callable $dicRegistrant Same format as ExtensionAccess::setRegistryBuilder
	 */
	public function __construct( array &$globalVars, $rootDirectory, $dicRegistrant ) {
		$this->globalVars =& $globalVars;
		$this->rootDirectory = $rootDirectory;
		$this->dicRegistrant = $dicRegistrant;
	}

	public function run() {
		$this->registerDic();
		$this->registerHooks();

		$this->registerCredits();
		$this->registerPermissions();
		$this->registerInternationalization();
		$this->registerWebAPI();
		$this->registerSpecialPages();
		$this->registerResources();
	}

	private function registerDic() {
		$builder = new WikibaseQueryBuilder( $this->globalVars );
		call_user_func(
			$this->dicRegistrant,
			array( $builder, 'build' )
		);
	}

	private function registerHooks() {
		$hookSetup = new HookSetup( $this->globalVars['wgHooks'], $this->rootDirectory );
		$hookSetup->run();
	}

	private function registerCredits() {
		$this->globalVars['wgExtensionCredits']['wikibase'][] = array(
			'path' => $this->rootDirectory,
			'name' => 'Wikibase Query',
			'version' => WIKIBASE_QUERY_VERSION,
			'author' => array(
				'The Wikidata team',
			),
			'url' => 'https://github.com/wmde/WikibaseQuery',
			'descriptionmsg' => 'wikibasequery-desc'
		);
	}

	private function registerInternationalization() {
		$this->globalVars['wgMessagesDirs']['WikibaseQuery'] = $this->rootDirectory . '/i18n';
		$this->globalVars['wgExtensionMessagesFiles']['WikibaseQuery'] = $this->rootDirectory . '/WikibaseQuery.i18n.php';
		$this->globalVars['wgExtensionMessagesFiles']['WikibaseQueryAliases'] = $this->rootDirectory . '/WikibaseQuery.i18n.aliases.php';
	}

	private function registerWebAPI() {
		$this->globalVars['wgAPIModules']['entitiesbypropertyvalue'] = 'Wikibase\Query\Api\EntitiesByPropertyValue';
	}

	private function registerSpecialPages() {
		// Special page registration
		$this->globalVars['wgSpecialPages']['SimpleQuery'] = 'Wikibase\Query\Specials\SimpleQuery';

		// Special page groups
		$this->globalVars['wgSpecialPageGroups']['SimpleQuery'] = 'wikibaserepo';
	}

	private function registerPermissions() {
		//wikibasequery permission
		$this->globalVars['wgGroupPermissions']['*']['wikibase-query-run'] = true;
	}

	private function registerResources() {
		$this->globalVars['wgResourceModules'] = array_merge(
			$this->globalVars['wgResourceModules'],
			include $this->rootDirectory . '/resources/resources.php'
		);

		$rootDirectory = $this->rootDirectory;

		$this->globalVars['wgHooks']['ResourceLoaderTestModules'][] = function(
			array &$testModules,
			\ResourceLoader &$resourceLoader
		) use( $rootDirectory ) {
			$testModules['qunit'] = array_merge(
				$testModules['qunit'],
				include( $rootDirectory . '/tests/qunit/resources.php' )
			);
			return true;
		};

	}

}
