<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use SilverstripeRector\Silverstripe51\Rector\Class_\RenameEnabledToIsEnabledOnBuildTaskRector;

return RectorConfig::configure()
    ->withRules([
        RenameEnabledToIsEnabledOnBuildTaskRector::class,
    ]);
