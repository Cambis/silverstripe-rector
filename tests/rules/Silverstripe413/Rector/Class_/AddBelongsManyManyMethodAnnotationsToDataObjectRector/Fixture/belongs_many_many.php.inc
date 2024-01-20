<?php

namespace SilverstripeRector\Tests\Silverstripe413\Rector\Class_\AddBelongsManyManyMethodAnnotationsToDataObjectRector\Fixture;

class BelongsManyMany extends \SilverStripe\ORM\DataObject implements \SilverStripe\Dev\TestOnly
{
}

?>
-----
<?php

namespace SilverstripeRector\Tests\Silverstripe413\Rector\Class_\AddBelongsManyManyMethodAnnotationsToDataObjectRector\Fixture;

/**
 * @method \SilverStripe\ORM\ManyManyList|\SilverstripeRector\Tests\Silverstripe413\Rector\Class_\AddBelongsManyManyMethodAnnotationsToDataObjectRector\Source\RelationMock[] BelongsManyManyRelationship()
 */
class BelongsManyMany extends \SilverStripe\ORM\DataObject implements \SilverStripe\Dev\TestOnly
{
}

?>
