<?php

declare(strict_types=1);

use Cambis\SilverstripeRector\Silverstripe54\Rector\StaticCall\SSViewerGetBaseTagRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([SSViewerGetBaseTagRector::class]);
