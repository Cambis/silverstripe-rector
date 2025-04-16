<?php

namespace SilverStripe\ORM;

use SilverStripe\Core\Config\Configurable;
use SilverStripe\Core\Extensible;
use SilverStripe\Core\Injector\Injectable;
use function class_exists;

if (class_exists('SilverStripe\ORM\DataObject')) {
    return;
}

class DataObject
{
    use Configurable;
    use Extensible;
    use Injectable;
}
