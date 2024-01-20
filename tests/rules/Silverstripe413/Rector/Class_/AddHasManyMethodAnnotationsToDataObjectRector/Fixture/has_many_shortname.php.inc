<?php

namespace SilverstripeRector\Tests\Silverstripe413\Rector\Class_\AddHasManyMethodAnnotationsToDataObjectRector\Fixture;

use SilverstripeRector\Tests\Silverstripe413\Rector\Class_\AddHasManyMethodAnnotationsToDataObjectRector\Source\RelationMock;
use SilverStripe\ORM\HasManyList;

/**
 * @method HasManyList|RelationMock[] HasManyRelationship()
 */
class HasManyShortname extends \SilverStripe\ORM\DataObject implements \SilverStripe\Dev\TestOnly
{
}

?>
-----
<?php

namespace SilverstripeRector\Tests\Silverstripe413\Rector\Class_\AddHasManyMethodAnnotationsToDataObjectRector\Fixture;

use SilverstripeRector\Tests\Silverstripe413\Rector\Class_\AddHasManyMethodAnnotationsToDataObjectRector\Source\RelationMock;
use SilverStripe\ORM\HasManyList;

/**
 * @method HasManyList|RelationMock[] HasManyRelationship()
 */
class HasManyShortname extends \SilverStripe\ORM\DataObject implements \SilverStripe\Dev\TestOnly
{
}

?>
