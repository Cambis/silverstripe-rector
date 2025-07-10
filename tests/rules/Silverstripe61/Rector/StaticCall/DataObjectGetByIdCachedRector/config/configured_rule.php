<?php

declare(strict_types=1);

use Cambis\SilverstripeRector\Silverstripe61\Rector\StaticCall\DataObjectGetByIdCachedRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([DataObjectGetByIdCachedRector::class]);
