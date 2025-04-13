<?php

namespace SilverStripe\View;

use SilverStripe\Core\Injector\Injectable;
use function class_exists;

if (class_exists('SilverStripe\View\ViewableData')) {
    return;
}

class ViewableData
{
    use Injectable;
}
