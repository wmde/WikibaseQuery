<?php

namespace Wikibase\Query;

use Wikibase\Query\Api\EntitiesByPropertyValue;

class WikibaseQuery {

	public function newEntitiesByPropertyValueModule() {
		return new EntitiesByPropertyValue();
	}

}
