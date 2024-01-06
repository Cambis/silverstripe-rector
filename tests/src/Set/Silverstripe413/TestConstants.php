<?php

declare(strict_types=1);

namespace SilverstripeRector\Tests\Set\Silverstripe413;

use SilverstripeRector\Tests\Set\Silverstripe413\Source\RelationMock;

final class TestConstants
{
    public const BELONGS_MANY_MANY_CONFIG = [
        'BelongsManyManyRelationship' => RelationMock::class,
    ];

    public const BELONGS_TO_CONFIG = [
        'BelongsToRelationship' => RelationMock::class . '.Parent',
    ];

    public const DB_CONFIG = [
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
        'Varchar' => 'Varchar(255)',
    ];

    public const HAS_ONE_CONFIG = [
        'HasOneRelationship' => RelationMock::class,
    ];

    public const HAS_MANY_CONFIG = [
        'HasManyRelationship' => RelationMock::class,
    ];

    public const MANY_MANY_CONFIG = [
        'ManyManyRelationship' => RelationMock::class,
    ];

    public const MANY_MANY_THROUGH_CONFIG = [
        'ManyManyThroughRelationship' => [
            'through' => RelationMock::class,
            'from' => '',
            'to' => '',
        ],
    ];
}
