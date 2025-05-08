<?php

declare(strict_types=1);

namespace SilverStripe\Core\Config;

use function trait_exists;

if (trait_exists('SilverStripe\Core\Config\Configurable')) {
    return;
}

trait Configurable
{
    public static function config(): Config_ForClass
    {
        return new Config_ForClass();
    }
}
