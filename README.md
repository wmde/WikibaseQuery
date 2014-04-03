# Wikibase Query

[![Build Status](https://secure.travis-ci.org/wmde/WikibaseQuery.png?branch=master)](http://travis-ci.org/wmde/WikibaseQuery)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/wmde/WikibaseQuery/badges/quality-score.png?s=0916f98c5cbdd030e32d936f98392a8e1e95f53f)](https://scrutinizer-ci.com/g/wmde/WikibaseQuery/)

On Packagist:
[![Latest Stable Version](https://poser.pugx.org/wikibase/query/version.png)](https://packagist.org/packages/wikibase/query)
[![Download count](https://poser.pugx.org/wikibase/query/d/total.png)](https://packagist.org/packages/wikibase/query)

Wikibase Query adds query capabilities to Wikibase Repo.

Features:

* New Query page type that allows people to define queries (in the Query namespace by default).
* Query execution against a query engine that returns a query result

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

## Technical documentation

All classes provided by WikibaseQuery reside in the Wikibase\Query namespace.

Public classes and interfaces have an @since tag denoting the version since which they can be accessed.
Constructs without an @since tag are package private and should not be used by extensions.

Object graph construction is done via the dependency injection container defined in Wikibase\Query\DIC.
Individual builders reside in Wikibase\Query\DIC\Builders. Builders are registered in
Wikibase\Query\DIC\WikibaseQueryBuilder. All access to the DIC from outside the DIC happens via
Wikibase\Query\DIC\WikibaseQuery. When requiring access to this class from a legacy API,
the ExtensionAccess::getWikibaseQuery method should be used. Usage of this method is forbidden
outside of legacy APIs in which we cannot achieve proper dependency construction.

## Tests

This library comes with a set up PHPUnit tests that cover all non-trivial code. You can run these
tests using the PHPUnit configuration file found in the root directory. The tests can also be run
via TravisCI, as a TravisCI configuration file is also provided in the root directory.

## Authors

Wikibase Query has been written by [Jeroen De Dauw](https://www.mediawiki.org/wiki/User:Jeroen_De_Dauw)
as [Wikimedia Germany](https://wikimedia.de) employee for the [Wikidata project](https://wikidata.org/).

Contributions where also made by several [other people](https://www.ohloh.net/p/wikibasequery/contributors).

## Links

* [Wikibase Query on Packagist](https://packagist.org/packages/wikibase/query)
* [Wikibase Query on Ohloh](https://www.ohloh.net/p/wikibasequery)
* [Wikibase Query on MediaWiki.org](https://www.mediawiki.org/wiki/Extension:Wikibase_Query)
* [TravisCI build status](https://travis-ci.org/wmde/WikibaseQuery)
