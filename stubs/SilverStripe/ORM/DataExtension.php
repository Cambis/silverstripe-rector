<?php

namespace SilverStripe\ORM;

use SilverStripe\Core\Extension;
use function class_exists;

if (class_exists('SilverStripe\ORM\DataExtension')) {
    return;
}

/**
 * @template T
 * @extends Extension<T>
 */
abstract class DataExtension extends Extension
{
}
