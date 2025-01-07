<?php

declare(strict_types=1);

use Cambis\SilverstripeRector\Configuration\SilverstripeOption;
use Cambis\SilverstripeRector\Set\ValueObject\SilverstripeSetList;
use Rector\Config\RectorConfig;
use Rector\Configuration\Option;
use Rector\Configuration\Parameter\SimpleParameterProvider;

SimpleParameterProvider::addParameter(Option::PHPSTAN_FOR_RECTOR_PATHS, [SilverstripeOption::PHPSTAN_FOR_RECTOR_INCLUDE_TEST_ONLY_PATH]);

return RectorConfig::configure()
    ->withSets([
        SilverstripeSetList::WITH_SILVERSTRIPE_API,
        SilverstripeSetList::WITH_RECTOR_SERVICES,
    ]);
