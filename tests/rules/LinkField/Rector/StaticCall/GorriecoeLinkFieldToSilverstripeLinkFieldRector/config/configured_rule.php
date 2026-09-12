<?php

declare(strict_types=1);

use Cambis\SilverstripeRector\LinkField\Rector\StaticCall\GorriecoeLinkFieldToSilverstripeLinkFieldRector;
use Cambis\SilverstripeRector\Set\ValueObject\SilverstripeSetList;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withSets([SilverstripeSetList::WITH_RECTOR_SERVICES])
    ->withRules([
        GorriecoeLinkFieldToSilverstripeLinkFieldRector::class,
    ]);
