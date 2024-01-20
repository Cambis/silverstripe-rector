<?php

namespace SilverstripeRector\Tests\Silverstripe52\Rector\Class_\DataListMethodAnnotationToGenericDataListMethodAnnotationRector\Fixture;

/**
 * @method \SilverStripe\ORM\DataObject HasOneRelationship()
 * @property int $HasOneRelationshipID
 * @method \SilverStripe\ORM\HasManyList HasManyRelationship()
 * @method \SilverStripe\ORM\ManyManyList ManyManyRelationship()
 * @method \SilverStripe\ORM\ManyManyThroughList ManyManyThroughRelationship()
 */
class ObjectType extends \SilverStripe\ORM\DataObject
{
}

?>
-----
<?php

namespace SilverstripeRector\Tests\Silverstripe52\Rector\Class_\DataListMethodAnnotationToGenericDataListMethodAnnotationRector\Fixture;

/**
 * @method \SilverStripe\ORM\DataObject HasOneRelationship()
 * @property int $HasOneRelationshipID
 * @method \SilverStripe\ORM\HasManyList<\SilverStripe\ORM\DataObject> HasManyRelationship()
 * @method \SilverStripe\ORM\ManyManyList<\SilverStripe\ORM\DataObject> ManyManyRelationship()
 * @method \SilverStripe\ORM\ManyManyThroughList<\SilverStripe\ORM\DataObject> ManyManyThroughRelationship()
 */
class ObjectType extends \SilverStripe\ORM\DataObject
{
}

?>
