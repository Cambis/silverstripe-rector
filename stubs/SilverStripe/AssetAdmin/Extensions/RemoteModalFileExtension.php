<?php

namespace SilverStripe\AssetAdmin\Extensions;

use function class_exists;

if (class_exists('SilverStripe\AssetAdmin\Extensions\RemoteModalFileExtension')) {
    return;
}

class RemoteModalFileExtension
{
}
