<?php

namespace SilverStripe\ORM\FieldType;

use function class_exists;

if (class_exists('SilverStripe\ORM\FieldType\DBDatetime')) {
    return;
}

class DBDatetime extends DBDate
{
}
