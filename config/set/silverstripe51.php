<?php

declare(strict_types=1);

use Cambis\SilverstripeRector\Silverstripe51\Rector\Class_\RenameEnabledToIsEnabledOnBuildTaskRector;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../config.php');
    $rectorConfig->rules([
        RenameEnabledToIsEnabledOnBuildTaskRector::class,
    ]);
};
