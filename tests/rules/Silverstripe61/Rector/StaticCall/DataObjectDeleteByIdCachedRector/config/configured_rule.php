<?php

declare(strict_types=1);

use Cambis\SilverstripeRector\Silverstripe61\Rector\StaticCall\DataObjectDeleteByIdCachedRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([DataObjectDeleteByIdCachedRector::class]);
