<?php

declare(strict_types=1);

use Cambis\SilverstripeRector\Configuration\SilverstripeOption;
use Cambis\SilverstripeRector\Set\ValueObject\SilverstripeSetList;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withSets([
        SilverstripeSetList::WITH_SILVERSTRIPE_API,
        SilverstripeSetList::WITH_RECTOR_SERVICES,
    ])
    ->withPHPStanConfigs([
        SilverstripeOption::PHPSTAN_FOR_RECTOR_INCLUDE_TEST_ONLY_PATH,
    ]);
