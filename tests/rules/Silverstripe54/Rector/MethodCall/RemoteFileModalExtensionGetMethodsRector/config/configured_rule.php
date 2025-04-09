<?php

declare(strict_types=1);

use Cambis\SilverstripeRector\Silverstripe54\Rector\MethodCall\RemoteFileModalExtensionGetMethodsRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([RemoteFileModalExtensionGetMethodsRector::class]);
