<?php

namespace SilverStripe\ORM;

use SilverStripe\Core\Injector\Injectable;
use function class_exists;

if (class_exists('SilverStripe\ORM\ArrayList')) {
    return;
}

class ArrayList
{
    use Injectable;

    public function __tostring(): string
    {
        return '';
    }
}
