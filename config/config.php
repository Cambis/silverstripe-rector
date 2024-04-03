<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use SilverstripeRector\Set\ValueObject\SilverstripeSetList;

return RectorConfig::configure()
    ->withSets([
        SilverstripeSetList::WITH_DEPENDENCY_INJECTION,
        SilverstripeSetList::WITH_SERVICES,
    ]);
