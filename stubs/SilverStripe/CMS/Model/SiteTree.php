<?php

namespace SilverStripe\CMS\Model;

use function class_exists;

if (class_exists('SilverStripe\CMS\Model\SiteTree')) {
    return;
}

class SiteTree
{
}
