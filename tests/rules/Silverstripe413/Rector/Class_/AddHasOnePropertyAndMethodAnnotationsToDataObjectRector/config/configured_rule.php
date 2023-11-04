<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use SilverstripeRector\Silverstripe413\Rector\Class_\AddHasOnePropertyAndMethodAnnotationsToDataObjectRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../../../../../../../config/config.php');
    $rectorConfig->rule(AddHasOnePropertyAndMethodAnnotationsToDataObjectRector::class);
};
