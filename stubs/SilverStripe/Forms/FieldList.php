<?php

namespace SilverStripe\Forms;

use SilverStripe\Core\Injector\Injectable;
use function class_exists;

if (class_exists('SilverStripe\Forms\FieldList')) {
    return;
}

class FieldList
{
    use Injectable;
}
