<?php

namespace Cambis\SilverstripeRector\Tests\Silverstripe413\Rector\Class_\AddManyManyMethodAnnotationsToDataObjectRector\Fixture;

class ManyMany extends \SilverStripe\ORM\DataObject implements \SilverStripe\Dev\TestOnly
{
}

?>
-----
<?php

namespace Cambis\SilverstripeRector\Tests\Silverstripe413\Rector\Class_\AddManyManyMethodAnnotationsToDataObjectRector\Fixture;

/**
 * @method \SilverStripe\ORM\ManyManyList|\Cambis\SilverstripeRector\Tests\Silverstripe413\Rector\Class_\AddManyManyMethodAnnotationsToDataObjectRector\Source\RelationMock[] ManyManyRelationship()
 */
class ManyMany extends \SilverStripe\ORM\DataObject implements \SilverStripe\Dev\TestOnly
{
}

?>
