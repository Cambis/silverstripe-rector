<?php

declare(strict_types=1);

namespace SilverstripeRector\ValueObject;

use PHPStan\Type\BooleanType;
use PHPStan\Type\FloatType;
use PHPStan\Type\IntegerType;
use PHPStan\Type\Type;
use SilverStripe\ORM\FieldType\DBBoolean;
use SilverStripe\ORM\FieldType\DBDecimal;
use SilverStripe\ORM\FieldType\DBField;
use SilverStripe\ORM\FieldType\DBFloat;
use SilverStripe\ORM\FieldType\DBInt;

interface SilverstripeConstants
{
    public const BELONGS_TO = 'belongs_to';

    public const DB = 'db';

    public const DEPENDENCIES = 'dependencies';

    public const EXTENSIONS = 'extensions';

    public const HAS_ONE = 'has_one';

    public const HAS_MANY = 'has_many';

    public const MANY_MANY = 'many_many';

    public const BELONGS_MANY_MANY = 'belongs_many_many';

    public const GET_OWNER = 'getOwner';

    public const CREATE = 'create';

    public const GET_BY_ID = 'get_by_id';

    public const GET = 'get';

    public const BY_ID = 'byID';

    /**
     * @var array<class-string<DBField>, class-string<Type>>
     */
    public const DBFIELD_TO_TYPE_MAPPING = [
        DBBoolean::class => BooleanType::class,
        DBDecimal::class => FloatType::class,
        DBFloat::class => FloatType::class,
        DBInt::class => IntegerType::class,
    ];
}
