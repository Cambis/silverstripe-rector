<?php

namespace SilverStripe\ORM\FieldType;

use function class_exists;

if (class_exists('SilverStripe\ORM\FieldType\DBTime')) {
    return;
}

class DBTime extends DBField
{
}
