<?php

namespace SilverStripe\Core\Injector;

use function trait_exists;

if (trait_exists('SilverStripe\Core\Injector\Injectable')) {
    return;
}

trait Injectable
{
    /**
     * @return static
     */
    public static function create(...$args)
    {
    }
}
