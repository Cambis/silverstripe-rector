<?php

namespace SilverStripe\Core;

use function class_exists;

if (class_exists('SilverStripe\Core\Extension')) {
    return;
}

/**
 * @template T
 */
abstract class Extension
{
    /**
     * @return T
     */
    public function getOwner()
    {
    }
}
