<?php

declare(strict_types=1);

use Cambis\SilverstripeRector\LinkField\Rector\Class_\SheadawsonLinkableToSilverstripeLinkRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([
        SheadawsonLinkableToSilverstripeLinkRector::class,
    ]);
