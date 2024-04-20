<?php

namespace Cambis\SilverstripeRector\Tests\Silverstripe413\Rector\Class_\AddHasOnePropertyAndMethodAnnotationsToDataObjectRector\Fixture;

class HasOne extends \SilverStripe\ORM\DataObject implements \SilverStripe\Dev\TestOnly
{
}

?>
-----
<?php

namespace Cambis\SilverstripeRector\Tests\Silverstripe413\Rector\Class_\AddHasOnePropertyAndMethodAnnotationsToDataObjectRector\Fixture;

/**
 * @property int $HasOneRelationshipID
 * @method \Cambis\SilverstripeRector\Tests\Silverstripe413\Rector\Class_\AddHasOnePropertyAndMethodAnnotationsToDataObjectRector\Source\RelationMock HasOneRelationship()
 */
class HasOne extends \SilverStripe\ORM\DataObject implements \SilverStripe\Dev\TestOnly
{
}

?>
