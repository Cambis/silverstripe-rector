<?php

namespace Cambis\SilverstripeRector\Tests\Silverstripe52\Rector\Class_\AddHasManyMethodAnnotationsToDataObjectRector\Fixture;

/**
 * @method \SilverStripe\ORM\HasManyList|Cambis\SilverstripeRector\Tests\Silverstripe52\Rector\Class_\AddHasManyMethodAnnotationsToDataObjectRector\Source\RelationMock[] HasManyRelationship()
 */
class HasManyFromSS4 extends \SilverStripe\ORM\DataObject implements \SilverStripe\Dev\TestOnly
{
}

?>
-----
<?php

namespace Cambis\SilverstripeRector\Tests\Silverstripe52\Rector\Class_\AddHasManyMethodAnnotationsToDataObjectRector\Fixture;

/**
 * @method \SilverStripe\ORM\HasManyList<\Cambis\SilverstripeRector\Tests\Silverstripe52\Rector\Class_\AddHasManyMethodAnnotationsToDataObjectRector\Source\RelationMock> HasManyRelationship()
 */
class HasManyFromSS4 extends \SilverStripe\ORM\DataObject implements \SilverStripe\Dev\TestOnly
{
}

?>
