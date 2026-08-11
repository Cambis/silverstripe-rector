<?php

declare(strict_types=1);

use Cambis\SilverstripeRector\Set\ValueObject\SilverstripeLevelSetList;
use Cambis\SilverstripeRector\Set\ValueObject\SilverstripeSetList;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/src',
    ])
    ->withSets([
        SilverstripeLevelSetList::UP_TO_SILVERSTRIPE_413,
        SilverstripeSetList::TYPE_DECLARATION_DOCBLOCKS,
    ]);
