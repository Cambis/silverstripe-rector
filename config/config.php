<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->bootstrapFiles([
        __DIR__ . '/../bootstrap.php',
    ]);

    $rectorConfig->phpstanConfigs([
        __DIR__ . '/services.neon.dist',
    ]);
};
