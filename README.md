# Wikibase Query

[![Build Status](https://secure.travis-ci.org/wikimedia/mediawiki-extensions-WikibaseQuery.png?branch=master)](http://travis-ci.org/wikimedia/mediawiki-extensions-WikibaseQuery)
[![Coverage Status](https://coveralls.io/repos/wikimedia/mediawiki-extensions-WikibaseQuery/badge.png?branch=master)](https://coveralls.io/r/wikimedia/mediawiki-extensions-WikibaseQuery?branch=master)

On Packagist:
[![Latest Stable Version](https://poser.pugx.org/wikibase/query/version.png)](https://packagist.org/packages/wikibase/query)
[![Download count](https://poser.pugx.org/wikibase/query/d/total.png)](https://packagist.org/packages/wikibase/query)

Wikibase Query adds query capabilities to Wikibase Repo.

Features:

* New Query page type that allows people to define queries (in the Query namespace by default).
* Query execution against a query engine that returns a query result

## Requirements

* PHP 5.3 or later
* Wikibase Repo 0.5 or later
* Wikibase QueryEngine 0.1 or later
* Ask 1.x
* DataValues 0.1 or later
* Serialization 2.x

## Installation

You can use [Composer](http://getcomposer.org/) to download and install
this package as well as its dependencies. Alternatively you can simply clone
the git repository and take care of loading yourself.

### Composer

To add this package as a local, per-project dependency to your project, simply add a
dependency on `wikibase/query` to your project's `composer.json` file.
Here is a minimal example of a `composer.json` file that just defines a dependency on
Wikibase Query 1.0:

    {
        "require": {
            "wikibase/query": "1.0.*"
        }
    }

## Tests

This library comes with a set up PHPUnit tests that cover all non-trivial code. You can run these
tests using the PHPUnit configuration file found in the root directory. The tests can also be run
via TravisCI, as a TravisCI configuration file is also provided in the root directory.

## Authors

Wikibase Query has been written by [Jeroen De Dauw](https://www.mediawiki.org/wiki/User:Jeroen_De_Dauw)
as [Wikimedia Germany](https://wikimedia.de) employee for the [Wikidata project](https://wikidata.org/).

## Links

* [Wikibase Query on Packagist](https://packagist.org/packages/wikibase/query)
* [Wikibase Query on Ohloh](https://www.ohloh.net/p/wikibasequery)
* [Wikibase Query on MediaWiki.org](https://www.mediawiki.org/wiki/Extension:Wikibase_Query)
* [TravisCI build status](https://travis-ci.org/wikimedia/mediawiki-extensions-WikibaseQuery)