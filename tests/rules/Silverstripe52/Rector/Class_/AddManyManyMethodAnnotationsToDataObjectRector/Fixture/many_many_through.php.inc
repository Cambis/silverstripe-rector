<?php

namespace Cambis\SilverstripeRector\Tests\Silverstripe52\Rector\Class_\AddManyManyMethodAnnotationsToDataObjectRector\Fixture;

class ManyManyThrough extends \SilverStripe\ORM\DataObject implements \SilverStripe\Dev\TestOnly
{
}

?>
-----
<?php

namespace Cambis\SilverstripeRector\Tests\Silverstripe52\Rector\Class_\AddManyManyMethodAnnotationsToDataObjectRector\Fixture;

/**
 * @method \SilverStripe\ORM\ManyManyThroughList<\Cambis\SilverstripeRector\Tests\Silverstripe52\Rector\Class_\AddManyManyMethodAnnotationsToDataObjectRector\Source\RelationMock> ManyManyThroughRelationship()
 */
class ManyManyThrough extends \SilverStripe\ORM\DataObject implements \SilverStripe\Dev\TestOnly
{
}

?>
