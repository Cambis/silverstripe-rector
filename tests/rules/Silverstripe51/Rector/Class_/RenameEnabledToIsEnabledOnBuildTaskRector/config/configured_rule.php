<?php

declare(strict_types=1);

use Cambis\SilverstripeRector\Silverstripe51\Rector\Class_\RenameEnabledToIsEnabledOnBuildTaskRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([
        RenameEnabledToIsEnabledOnBuildTaskRector::class,
    ]);
