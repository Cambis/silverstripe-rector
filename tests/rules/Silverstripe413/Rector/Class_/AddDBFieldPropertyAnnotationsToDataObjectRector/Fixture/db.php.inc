<?php

namespace SilverstripeRector\Tests\Silverstripe413\Rector\Class_\AddDBFieldPropertyAnnotationsToDataObjectRector\Fixture;

class DB extends \SilverStripe\ORM\DataObject implements \SilverStripe\Dev\TestOnly
{
}

?>
-----
<?php

namespace SilverstripeRector\Tests\Silverstripe413\Rector\Class_\AddDBFieldPropertyAnnotationsToDataObjectRector\Fixture;

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
 */
class DB extends \SilverStripe\ORM\DataObject implements \SilverStripe\Dev\TestOnly
{
}

?>
