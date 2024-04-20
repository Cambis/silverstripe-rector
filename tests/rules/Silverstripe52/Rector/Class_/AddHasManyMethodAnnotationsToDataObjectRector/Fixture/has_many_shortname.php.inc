<?php

namespace Cambis\SilverstripeRector\Tests\Silverstripe52\Rector\Class_\AddHasManyMethodAnnotationsToDataObjectRector\Fixture;

use Cambis\SilverstripeRector\Tests\Silverstripe52\Rector\Class_\AddHasManyMethodAnnotationsToDataObjectRector\Source\RelationMock;
use SilverStripe\ORM\HasManyList;

/**
 * @method HasManyList<RelationMock> HasManyRelationship()
 */
class HasManyShortname extends \SilverStripe\ORM\DataObject implements \SilverStripe\Dev\TestOnly
{
}
