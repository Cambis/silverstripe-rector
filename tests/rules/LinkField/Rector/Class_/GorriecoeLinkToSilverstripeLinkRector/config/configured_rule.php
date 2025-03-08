<?php

declare(strict_types=1);

use Cambis\SilverstripeRector\LinkField\Rector\Class_\GorriecoeLinkToSilverstripeLinkRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([
        GorriecoeLinkToSilverstripeLinkRector::class,
    ]);
