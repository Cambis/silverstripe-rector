<?php

declare(strict_types=1);

use Cambis\SilverstripeRector\Silverstripe52\Rector\Class_\RemoveGetOwnerMethodAnnotationFromExtensionsRector;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../../../../../../../config/config.php');
    $rectorConfig->rule(RemoveGetOwnerMethodAnnotationFromExtensionsRector::class);
};
