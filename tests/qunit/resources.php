<?php

return call_user_func( function() {
	$remoteExtPathParts = explode( DIRECTORY_SEPARATOR . 'extensions' . DIRECTORY_SEPARATOR, __DIR__, 2 );
	$moduleTemplate = array(
		'localBasePath' => __DIR__,
		'remoteExtPath' => $remoteExtPathParts[1],
	);

	$modules = array(
		'wikibase.query.SimpleQueryForm.tests' => $moduleTemplate + array(
			'scripts' => array(
				'wikibase.query.SimpleQueryForm.tests.js'
			),
			'dependencies' => array(
				'wikibase.query.SimpleQueryForm',
				'dataValues.values',
				'wikibase.store.EntityStore'
			)
		),
	);

	return $modules;
} );
