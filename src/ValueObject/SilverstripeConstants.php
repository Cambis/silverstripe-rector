<?php

declare(strict_types=1);

namespace SilverstripeRector\ValueObject;

final class SilverstripeConstants
{
    public const PROPERTY_BELONGS_TO = 'belongs_to';

    public const PROPERTY_DB = 'db';

    public const PROPERTY_DEPENDENCIES = 'dependencies';

    public const PROPERTY_EXTENSIONS = 'extensions';

    public const PROPERTY_HAS_ONE = 'has_one';

    public const PROPERTY_HAS_MANY = 'has_many';

    public const PROPERTY_MANY_MANY = 'many_many';

    public const PROPERTY_BELONGS_MANY_MANY = 'belongs_many_many';

    public const METHOD_GET_OWNER = 'getOwner';

    public const METHOD_CREATE = 'create';

    public const METHOD_GET_BY_ID = 'get_by_id';

    public const METHOD_GET = 'get';

    public const METHOD_BY_ID = 'byID';
}
