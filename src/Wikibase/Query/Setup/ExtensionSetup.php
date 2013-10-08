<?php

namespace Wikibase\Query\Setup;

use Wikibase\Query\DIC\ExtensionAccess;

/**
 * @since 0.1
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class ExtensionSetup {

	protected $globalVars;
	protected $rootDirectory;
	protected $dicRegistrant;

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
