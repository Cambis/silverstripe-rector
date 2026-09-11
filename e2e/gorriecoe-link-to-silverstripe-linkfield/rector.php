<?php

declare(strict_types=1);

use Cambis\SilverstripeRector\Set\ValueObject\SilverstripeSetList;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withImportNames(removeUnusedImports: true)
    ->withPaths([
        __DIR__ . '/app/src',
    ])
    ->withSets([
        SilverstripeSetList::GORRIECOE_LINK_TO_SILVERSTRIPE_LINKFIELD,
    ]);
