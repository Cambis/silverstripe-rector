<?php

namespace SilverStripe\Assets;

use SilverStripe\ORM\DataObject;
use function class_exists;

if (class_exists('SilverStripe\Assets\File')) {
    return;
}

class File extends DataObject
{
}
