<?php

declare(strict_types=1);

use Cambis\SilverstripeRector\CodeQuality\Rector\PropertyFetch\ExtensionOwnerToGetOwnerRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([ExtensionOwnerToGetOwnerRector::class]);
