<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use SilverstripeRector\Tests\Silverstripe52\Rector\Class_\AddExtendsAnnotationToExtensionRector\IntersectionMultipleRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../../../../../../../config/config.php');
    $rectorConfig->rule(IntersectionMultipleRector::class);
};