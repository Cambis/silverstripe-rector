<?php

namespace SilverStripe\Core;

use function class_exists;

if (class_exists('SilverStripe\Core\Extension')) {
    return;
}

abstract class Extension
{
}
