<?php

namespace SilverStripe\ORM\FieldType;

use function class_exists;

if (class_exists('SilverStripe\ORM\FieldType\DBField')) {
    return;
}

abstract class DBField
{
    /**
     * @var mixed
     */
    protected $value;

    public function __get($name)
    {
    }
}
