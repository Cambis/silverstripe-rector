<?php

namespace SilverStripe\Core\Config;

use function trait_exists;

if (trait_exists('SilverStripe\Core\Config\Configurable')) {
    return;
}

trait Configurable
{
}
