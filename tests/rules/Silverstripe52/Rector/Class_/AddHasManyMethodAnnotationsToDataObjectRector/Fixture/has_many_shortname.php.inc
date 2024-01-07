<?php

namespace SilverstripeRector\Tests\Silverstripe52\Rector\Class_\AddHasManyMethodAnnotationsToDataObjectRector\Fixture;

use SilverstripeRector\Tests\Silverstripe52\Rector\Class_\AddHasManyMethodAnnotationsToDataObjectRector\Source\RelationMock;
use SilverStripe\ORM\HasManyList;

/**
 * @method HasManyList<RelationMock> HasManyRelationship()
 */
class HasManyMock extends \SilverStripe\ORM\DataObject implements \SilverStripe\Dev\TestOnly
{
}
