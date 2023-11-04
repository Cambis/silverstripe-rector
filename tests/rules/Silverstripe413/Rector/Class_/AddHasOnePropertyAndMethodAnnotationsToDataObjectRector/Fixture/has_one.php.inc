<?php

namespace SilverstripeRector\Tests\Silverstripe413\Rector\Class_\AddHasOnePropertyAndMethodAnnotationsToDataObjectRector\Fixture;

class HasOneMock extends \SilverStripe\ORM\DataObject implements \SilverStripe\Dev\TestOnly
{
}

?>
-----
<?php

namespace SilverstripeRector\Tests\Silverstripe413\Rector\Class_\AddHasOnePropertyAndMethodAnnotationsToDataObjectRector\Fixture;

/**
 * @property int $HasOneRelationshipID
 * @method \SilverstripeRector\Tests\Silverstripe413\Rector\Class_\AddHasOnePropertyAndMethodAnnotationsToDataObjectRector\Source\RelationMock HasOneRelationship()
 */
class HasOneMock extends \SilverStripe\ORM\DataObject implements \SilverStripe\Dev\TestOnly
{
}

?>
