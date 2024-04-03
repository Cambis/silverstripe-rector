<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use SilverstripeRector\Silverstripe52\Rector\Class_\AddExtendsAnnotationToContentControllerRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../../../../../../../config/config.php');
    $rectorConfig->rule(AddExtendsAnnotationToContentControllerRector::class);
};
