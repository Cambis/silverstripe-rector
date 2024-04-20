<?php

namespace Cambis\SilverstripeRector\Tests\Silverstripe413\Rector\Class_\AddBelongsManyManyMethodAnnotationsToDataObjectRector\Fixture;

class BelongsManyMany extends \SilverStripe\ORM\DataObject implements \SilverStripe\Dev\TestOnly
{
}

?>
-----
<?php

namespace Cambis\SilverstripeRector\Tests\Silverstripe413\Rector\Class_\AddBelongsManyManyMethodAnnotationsToDataObjectRector\Fixture;

/**
 * @method \SilverStripe\ORM\ManyManyList|\Cambis\SilverstripeRector\Tests\Silverstripe413\Rector\Class_\AddBelongsManyManyMethodAnnotationsToDataObjectRector\Source\RelationMock[] BelongsManyManyRelationship()
 */
class BelongsManyMany extends \SilverStripe\ORM\DataObject implements \SilverStripe\Dev\TestOnly
{
}

?>
