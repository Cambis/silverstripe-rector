<?php

namespace SilverStripe\Core;

use function trait_exists;

if (trait_exists('SilverStripe\Core\Extensible')) {
    return;
}

trait Extensible
{
}
