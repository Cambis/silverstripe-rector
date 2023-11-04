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
class DBMock extends \SilverStripe\ORM\DataObject
{
    /**
     * @config
     */
    private static array $db = [
        'Boolean' => 'Boolean',
        'Currency' => 'Currency',
        'Date' => 'Date',
        'Decimal' => 'Decimal',
        'Enum' => 'Enum',
        'HTMLText' => 'HTMLText',
        'HTMLVarchar' => 'HTMLVarchar',
        'Int' => 'Int',
        'Percentage' => 'Percentage',
        'Datetime' => 'Datetime',
        'Text' => 'Text',
        'Time' => 'Time',
        'Varchar' => 'Varchar',
    ];
}
