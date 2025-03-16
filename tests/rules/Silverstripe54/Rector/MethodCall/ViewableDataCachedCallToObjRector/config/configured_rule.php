<?php

declare(strict_types=1);

use Cambis\SilverstripeRector\Silverstripe54\Rector\MethodCall\ViewableDataCachedCallToObjRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([ViewableDataCachedCallToObjRector::class]);
