<?php

namespace SilverStripe\ORM\FieldType;

use function class_exists;

if (class_exists('SilverStripe\ORM\FieldType\DBInt')) {
    return;
}

class DBInt extends DBField
{
}
