<?php

namespace SilverStripe\ORM;

use function class_exists;

if (class_exists('SilverStripe\ORM\DataObject')) {
    return;
}

class DataObject
{
}
