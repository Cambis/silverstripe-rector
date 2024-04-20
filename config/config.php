<?php

declare(strict_types=1);

use Cambis\SilverstripeRector\Set\ValueObject\SilverstripeSetList;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withSets([
        SilverstripeSetList::WITH_DEPENDENCY_INJECTION,
        SilverstripeSetList::WITH_SERVICES,
    ]);
