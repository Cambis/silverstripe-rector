<?php

declare(strict_types=1);

use Cambis\SilverstripeRector\LinkField\Rector\Class_\SilverstripeLinkLegacyRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([
        SilverstripeLinkLegacyRector::class,
    ]);
