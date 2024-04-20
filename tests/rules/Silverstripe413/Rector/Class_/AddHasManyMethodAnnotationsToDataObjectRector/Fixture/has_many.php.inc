<?php

namespace Cambis\SilverstripeRector\Tests\Silverstripe413\Rector\Class_\AddHasManyMethodAnnotationsToDataObjectRector\Fixture;

class HasMany extends \SilverStripe\ORM\DataObject implements \SilverStripe\Dev\TestOnly
{
}

?>
-----
<?php

namespace Cambis\SilverstripeRector\Tests\Silverstripe413\Rector\Class_\AddHasManyMethodAnnotationsToDataObjectRector\Fixture;

/**
 * @method \SilverStripe\ORM\HasManyList|\Cambis\SilverstripeRector\Tests\Silverstripe413\Rector\Class_\AddHasManyMethodAnnotationsToDataObjectRector\Source\RelationMock[] HasManyRelationship()
 */
class HasMany extends \SilverStripe\ORM\DataObject implements \SilverStripe\Dev\TestOnly
{
}

?>
