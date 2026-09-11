<?php

declare(strict_types=1);

use Cambis\SilverstripeRector\Set\ValueObject\SilverstripeSetList;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/app',
    ])
    ->withSets([
        SilverstripeSetList::COMPOSER_BASED,
        SilverstripeSetList::TYPE_DECLARATION_DOCBLOCKS,
    ]);
