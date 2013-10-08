<?php

namespace Wikibase\Query\Setup;

use Wikibase\Query\DIC\ExtensionAccess;

class ExtensionSetup {

	protected $globalVars;
	protected $rootDirectory;
	protected $dicRegistrant;

	public function __construct( array $globalVars, $rootDirectory, $dicRegistrant ) {
		$this->globalVars = $globalVars;
		$this->rootDirectory = $rootDirectory;
		$this->dicRegistrant = $dicRegistrant;
	}

	public function run() {
		$this->registerDic();
		$this->registerHooks();

		$this->registerCredits();
		$this->registerInternationalization();
		$this->registerWebAPI();
		$this->registerSpecialPages();
	}

	protected function registerDic() {
		$builder = new WikibaseQueryBuilder();
		call_user_func(
			$this->dicRegistrant,
			array( $builder, 'build' )
		);
	}

	protected function registerHooks() {
		$hookSetup = new HookSetup( $this->globalVars['wgHooks'], $this->rootDirectory );
		$hookSetup->run();
	}

	protected function registerCredits() {
		$this->globalVars['wgExtensionCredits']['wikibase'][] = array(
			'path' => $this->rootDirectory,
			'name' => 'Wikibase Query',
			'version' => WIKIBASE_QUERY_VERSION,
			'author' => array(
				'[https://www.mediawiki.org/wiki/User:Jeroen_De_Dauw Jeroen De Dauw]',
			),
			'url' => 'https://www.mediawiki.org/wiki/Extension:Wikibase_Query',
			'descriptionmsg' => 'wikibasequery-desc'
		);
	}

	protected function registerInternationalization() {
		$this->globalVars['wgExtensionMessagesFiles']['WikibaseQuery'] = $this->rootDirectory . '/WikibaseQuery.i18n.php';
		$this->globalVars['wgExtensionMessagesFiles']['WikibaseQueryAliases'] = $this->rootDirectory . '/WikibaseQuery.i18n.aliases.php';
	}

	protected function registerWebAPI() {
		$this->globalVars['wgAPIModules']['entitiesByPropertyValue'] = 'Wikibase\Query\Api\EntitiesByPropertyValue';
	}

	protected function registerSpecialPages() {
		// Special page registration
		$this->globalVars['wgSpecialPages']['SimpleQuery'] = 'Wikibase\Query\Specials\SimpleQuery';

		// Special page groups
		$this->globalVars['wgSpecialPageGroups']['SimpleQuery'] = 'wikibaserepo';
	}

}
