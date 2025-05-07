<?php

declare(strict_types=1);

namespace SilverStripe\Core\Config;

use function class_exists;

if (class_exists('SilverStripe\Core\Config\Config_ForClass')) {
    return;
}

class Config_ForClass
{
}
