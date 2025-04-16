<?php

namespace SilverStripe\ORM;

use function class_exists;

if (class_exists('SilverStripe\ORM\HasManyList')) {
    return;
}

class HasManyList
{
}
