<?php

namespace SilverstripeRector\Tests\Silverstripe413\Rector\Class_\AddBelongsToPropertyAndMethodAnnotationsToDataObjectRector\Fixture;

class BelongsToMock extends \SilverStripe\ORM\DataObject implements \SilverStripe\Dev\TestOnly
{
}

?>
-----
<?php

namespace SilverstripeRector\Tests\Silverstripe413\Rector\Class_\AddBelongsToPropertyAndMethodAnnotationsToDataObjectRector\Fixture;

/**
 * @property int $BelongsToRelationshipID
 * @method \SilverstripeRector\Tests\Silverstripe413\Rector\Class_\AddBelongsToPropertyAndMethodAnnotationsToDataObjectRector\Source\RelationMock BelongsToRelationship()
 */
class BelongsToMock extends \SilverStripe\ORM\DataObject implements \SilverStripe\Dev\TestOnly
{
}

?>
