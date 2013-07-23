<?php

namespace Wikibase\Query;

use Ask\DeserializerFactory;
use Ask\Language\Query;
use Ask\SerializerFactory;
use InvalidArgumentException;
use MWException;
use RuntimeException;
use Wikibase\Entity;
use Wikibase\Repo\WikibaseRepo;

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
	 * @var Query
	 */
	protected $query = null;

	public function __construct( Query $query ) {
		$this->query = $query;

		parent::__construct( array() );
	}

	/**
	 * Returns the Query of the query entity.
	 *
	 * @since 0.1
	 *
	 * @return Query
	 * @throws RuntimeException
	 */
	public function getQuery() {
		return $this->query;
	}

	/**
	 * Sets the Query of the query entity.
	 *
	 * @since 0.1
	 *
	 * @param Query $queryDefinition
	 */
	public function setQuery( Query $queryDefinition ) {
		$this->query = $queryDefinition;
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

//	/**
//	 * @see Entity::stub
//	 */
//	public function stub() {
//		parent::stub();
//
//		if ( $this->query !== null ) {
//			$serializerFactory = new SerializerFactory();
//
//			$this->data['query'] = $serializerFactory->newQuerySerializer()->serialize( $this->query );
//			$this->query = null;
//		}
//	}
//
//	/**
//	 * @see Entity::unstubQuery
//	 */
//	protected function unstubQuery() {
//		if( $this->query === null && array_key_exists( 'query', $this->data ) ) {
//			$deserializerFactory = new DeserializerFactory( WikibaseRepo::getDefaultInstance()->getDataValueFactory() );
//
//			$this->query = $deserializerFactory->newQueryDeSerializer()->deserialize( $this->data['query'] );
//		}
//	}

}
