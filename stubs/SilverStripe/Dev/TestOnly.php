<?php

namespace SilverStripe\Dev;

use function interface_exists;

if (interface_exists('SilverStripe\Dev\TestOnly')) {
    return;
}

interface TestOnly
{
}
