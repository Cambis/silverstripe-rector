<?php

namespace Cambis\SilverstripeRector\Tests\Silverstripe413\Rector\Class_\AddDBFieldPropertyAnnotationsToDataObjectRector\Fixture;

/**
 * @property mixed $Boolean
 * @property mixed $Currency
 * @property mixed $Date
 * @property mixed $Decimal
 * @property mixed $Enum
 * @property mixed $HTMLText
 * @property mixed $HTMLVarchar
 * @property mixed $Int
 * @property mixed $Percentage
 * @property mixed $Datetime
 * @property mixed $Text
 * @property mixed $Time
 * @property mixed $Varchar
 */
class DBIncomplete extends \SilverStripe\ORM\DataObject implements \SilverStripe\Dev\TestOnly
{
}

?>
-----
<?php

namespace Cambis\SilverstripeRector\Tests\Silverstripe413\Rector\Class_\AddDBFieldPropertyAnnotationsToDataObjectRector\Fixture;

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
class DBIncomplete extends \SilverStripe\ORM\DataObject implements \SilverStripe\Dev\TestOnly
{
}

?>
