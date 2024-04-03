<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

return RectorConfig::configure()
    // Gain access to the Injector and Configuration APIs
    ->withBootstrapFiles([
        __DIR__ . '/../bootstrap.php',
    ]);
