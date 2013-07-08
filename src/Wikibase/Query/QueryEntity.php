<?php

namespace Wikibase\Query;

use Ask\Language\Query;
use MWException;
use Wikibase\Entity;

/**
 * Represents a single Wikibase query.
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
 * @ingroup WikibaseDataModel
 * @ingroup WikibaseQuery
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class QueryEntity extends Entity {

	const ENTITY_TYPE = 'query';

	/**
	 * @since 0.1
	 *
	 * @var Query|null
	 */
	protected $queryDefinition = null;

	/**
	 * Returns the Query of the query entity.
	 *
	 * @since 0.1
	 *
	 * @return Query
	 * @throws MWException
	 */
	public function getQuery() {
		if ( $this->queryDefinition === null ) {
			if ( array_key_exists( 'querydefinition', $this->data ) ) {
				// TODO
			}
			else {
				throw new MWException( 'The Query of the query is not known' );
			}
		}

		return $this->queryDefinition;
	}

	/**
	 * Sets the Query of the query entity.
	 *
	 * @since 0.1
	 *
	 * @param Query $queryDefinition
	 */
	public function setQuery( Query $queryDefinition ) {
		$this->queryDefinition = $queryDefinition;
	}

	/**
	 * @see Entity::newFromArray
	 *
	 * @since 0.1
	 *
	 * @param array $data
	 *
	 * @return QueryEntity
	 */
	public static function newFromArray( array $data ) {
		return new static( $data );
	}

	/**
	 * @since 0.1
	 *
	 * @return QueryEntity
	 */
	public static function newEmpty() {
		return self::newFromArray( array() );
	}

	/**
	 * @see Entity::getType
	 *
	 * @since 0.1
	 *
	 * @return string
	 */
	public function getType() {
		return QueryEntity::ENTITY_TYPE;
	}

}
