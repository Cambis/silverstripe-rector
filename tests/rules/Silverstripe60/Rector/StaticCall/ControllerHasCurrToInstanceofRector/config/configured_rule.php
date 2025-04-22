<?php

declare(strict_types=1);

use Cambis\SilverstripeRector\Silverstripe60\Rector\StaticCall\ControllerHasCurrToInstanceofRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([
        ControllerHasCurrToInstanceofRector::class,
    ]);
