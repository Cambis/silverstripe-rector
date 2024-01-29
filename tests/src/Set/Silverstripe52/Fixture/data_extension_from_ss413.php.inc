<?php

namespace SilverstripeRector\Tests\Set\Silverstripe52\Fixture;

/**
 * @property bool $Boolean
 * @property float $Currency
 * @property string $Date
 * @property float $Decimal
 * @property string $Enum
 * @property string $HTMLText
 * @property string $HTMLVarchar
 * @property int $Int
 * @property float $Percentage
 * @property string $Datetime
 * @property string $Text
 * @property string $Time
 * @property string $Varchar
 * @property int $BelongsToRelationshipID
 * @method \SilverstripeRector\Tests\Set\Silverstripe52\Source\RelationMock BelongsToRelationship()
 * @property int $HasOneRelationshipID
 * @method \SilverstripeRector\Tests\Set\Silverstripe52\Source\RelationMock HasOneRelationship()
 * @method \SilverStripe\ORM\HasManyList|\SilverstripeRector\Tests\Set\Silverstripe52\Source\RelationMock[] HasManyRelationship()
 * @method \SilverStripe\ORM\ManyManyList|\SilverstripeRector\Tests\Set\Silverstripe52\Source\RelationMock[] BelongsManyManyRelationship()
 * @method \SilverStripe\ORM\ManyManyList|\SilverstripeRector\Tests\Set\Silverstripe52\Source\RelationMock[] ManyManyRelationship()
 * @method \SilverStripe\ORM\ManyManyThroughList|\SilverstripeRector\Tests\Set\Silverstripe52\Source\RelationMock[] ManyManyThroughRelationship()
 * @method static getOwner()
 */
class DataExtensionFromSS413 extends \SilverStripe\ORM\DataExtension implements \SilverStripe\Dev\TestOnly
{
}

?>
-----
<?php

namespace SilverstripeRector\Tests\Set\Silverstripe52\Fixture;

/**
 * @property bool $Boolean
 * @property float $Currency
 * @property string $Date
 * @property float $Decimal
 * @property string $Enum
 * @property string $HTMLText
 * @property string $HTMLVarchar
 * @property int $Int
 * @property float $Percentage
 * @property string $Datetime
 * @property string $Text
 * @property string $Time
 * @property string $Varchar
 * @property int $BelongsToRelationshipID
 * @method \SilverstripeRector\Tests\Set\Silverstripe52\Source\RelationMock BelongsToRelationship()
 * @property int $HasOneRelationshipID
 * @method \SilverstripeRector\Tests\Set\Silverstripe52\Source\RelationMock HasOneRelationship()
 * @method \SilverStripe\ORM\HasManyList<\SilverstripeRector\Tests\Set\Silverstripe52\Source\RelationMock> HasManyRelationship()
 * @method \SilverStripe\ORM\ManyManyList<\SilverstripeRector\Tests\Set\Silverstripe52\Source\RelationMock> BelongsManyManyRelationship()
 * @method \SilverStripe\ORM\ManyManyList<\SilverstripeRector\Tests\Set\Silverstripe52\Source\RelationMock> ManyManyRelationship()
 * @method \SilverStripe\ORM\ManyManyThroughList<\SilverstripeRector\Tests\Set\Silverstripe52\Source\RelationMock> ManyManyThroughRelationship()
 * @extends \SilverStripe\ORM\DataExtension<static>
 */
class DataExtensionFromSS413 extends \SilverStripe\ORM\DataExtension implements \SilverStripe\Dev\TestOnly
{
}

?>