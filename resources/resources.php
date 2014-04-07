<?php

return call_user_func( function() {
	$remoteExtPathParts = explode( DIRECTORY_SEPARATOR . 'extensions' . DIRECTORY_SEPARATOR, __DIR__, 2 );
	$moduleTemplate = array(
		'localBasePath' => __DIR__,
		'remoteExtPath' => $remoteExtPathParts[1],
	);

	$modules = array(
		'wikibase.query' => $moduleTemplate + array(
			'scripts' => array(
				'wikibase.query.js'
			),
			'dependencies' => array(
				'wikibase'
			)
		),
		'wikibase.query.special.simplequery' => $moduleTemplate + array(
			'scripts' => array(
				'wikibase.query.special.simplequery.js'
			),
			'dependencies' => array(
				'wikibase.query',
				'wikibase.query.SimpleQueryForm',
				'wikibase.store.EntityStore',
			)
		),
		'wikibase.query.SimpleQueryForm' => $moduleTemplate + array(
			'scripts' => array(
				'wikibase.query.SimpleQueryForm.js'
			),
			'dependencies' => array(
				'dataValues.values',
				'jquery.wikibase.snakview',
				'wikibase.common', // For the stylesheet
				'wikibase.query'
			)
		),
	);

	return $modules;
} );
