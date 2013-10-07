#! /bin/bash

set -x

cd ..

git clone https://gerrit.wikimedia.org/r/p/mediawiki/core.git phase3 --depth 1

cd -
cd ../phase3/extensions

mkdir WikibaseQuery

cd -
cp -r * ../phase3/extensions/WikibaseQuery

cd ../phase3

mysql -e 'create database its_a_mw;'
php maintenance/install.php --dbtype $DBTYPE --dbuser root --dbname its_a_mw --dbpath $(pwd) --pass nyan TravisWiki admin

cd extensions/WikibaseQuery
composer install

cd ../..
echo 'require_once( __DIR__ . "/vendor/wikibase/wikibase/repo/Wikibase.php" );' >> LocalSettings.php
echo 'require_once( __DIR__ . "/vendor/wikibase/wikibase/repo/ExampleSettings.php" );' >> LocalSettings.php
echo 'require_once( __DIR__ . "/vendor/wikibase/query/WikibaseQuery.php" );' >> LocalSettings.php

echo 'error_reporting(E_ALL| E_STRICT);' >> LocalSettings.php
echo 'ini_set("display_errors", 1);' >> LocalSettings.php
echo '$wgShowExceptionDetails = true;' >> LocalSettings.php
echo '$wgDevelopmentWarnings = true;' >> LocalSettings.php
echo "putenv( 'MW_INSTALL_PATH=$(pwd)' );" >> LocalSettings.php

php maintenance/update.php --quick
