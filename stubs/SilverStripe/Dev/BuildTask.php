<?php

namespace SilverStripe\Dev;

use function class_exists;

if (class_exists('SilverStripe\Dev\BuildTask')) {
    return;
}

abstract class BuildTask
{
}
