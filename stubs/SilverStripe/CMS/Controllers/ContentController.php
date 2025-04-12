<?php

namespace SilverStripe\CMS\Controllers;

use function class_exists;

if (class_exists('SilverStripe\CMS\Controllers\ContentController')) {
    return;
}

class ContentController
{
}
