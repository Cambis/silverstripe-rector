<?php

declare(strict_types=1);

use Cambis\SilverstripeRector\CodeQuality\Rector\Assign\ConfigurationPropertyFetchToMethodCallRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([ConfigurationPropertyFetchToMethodCallRector::class]);
