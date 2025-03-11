<?php

declare(strict_types=1);

use Cambis\SilverstripeRector\LinkField\Rector\StaticCall\SheadawsonLinkableFieldToSilverstripeLinkFieldRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([
        SheadawsonLinkableFieldToSilverstripeLinkFieldRector::class,
    ]);
