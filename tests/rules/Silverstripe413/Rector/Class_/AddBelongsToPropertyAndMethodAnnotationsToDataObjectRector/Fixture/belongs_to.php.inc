<?php

namespace Cambis\SilverstripeRector\Tests\Silverstripe413\Rector\Class_\AddBelongsToPropertyAndMethodAnnotationsToDataObjectRector\Fixture;

class BelongsTo extends \SilverStripe\ORM\DataObject implements \SilverStripe\Dev\TestOnly
{
}

?>
-----
<?php

namespace Cambis\SilverstripeRector\Tests\Silverstripe413\Rector\Class_\AddBelongsToPropertyAndMethodAnnotationsToDataObjectRector\Fixture;

/**
 * @property int $BelongsToRelationshipID
 * @method \Cambis\SilverstripeRector\Tests\Silverstripe413\Rector\Class_\AddBelongsToPropertyAndMethodAnnotationsToDataObjectRector\Source\RelationMock BelongsToRelationship()
 */
class BelongsTo extends \SilverStripe\ORM\DataObject implements \SilverStripe\Dev\TestOnly
{
}

?>
