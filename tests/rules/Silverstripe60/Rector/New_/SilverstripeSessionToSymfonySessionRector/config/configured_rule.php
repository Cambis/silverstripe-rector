<?php

declare(strict_types=1);

use Cambis\SilverstripeRector\Silverstripe60\Rector\New_\SilverstripeSessionToSymfonySessionRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([
        SilverstripeSessionToSymfonySessionRector::class,
    ]);
