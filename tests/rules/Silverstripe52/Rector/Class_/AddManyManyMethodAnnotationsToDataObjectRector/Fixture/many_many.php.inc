<?php

namespace SilverstripeRector\Tests\Silverstripe52\Rector\Class_\AddManyManyMethodAnnotationsToDataObjectRector\Fixture;

class ManyMany extends \SilverStripe\ORM\DataObject implements \SilverStripe\Dev\TestOnly
{
}

?>
-----
<?php

namespace SilverstripeRector\Tests\Silverstripe52\Rector\Class_\AddManyManyMethodAnnotationsToDataObjectRector\Fixture;

/**
 * @method \SilverStripe\ORM\ManyManyList<\SilverstripeRector\Tests\Silverstripe52\Rector\Class_\AddManyManyMethodAnnotationsToDataObjectRector\Source\RelationMock> ManyManyRelationship()
 */
class ManyMany extends \SilverStripe\ORM\DataObject implements \SilverStripe\Dev\TestOnly
{
}

?>
