<?php

declare(strict_types=1);

use Cambis\SilverstripeRector\Set\ValueObject\SilverstripeLevelSetList;
use Cambis\SilverstripeRector\Set\ValueObject\SilverstripeSetList;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/app',
    ])
    ->withSets([
        SilverstripeLevelSetList::UP_TO_SILVERSTRIPE_50,
        SilverstripeSetList::TYPE_DECLARATION_DOCBLOCKS,
    ]);
