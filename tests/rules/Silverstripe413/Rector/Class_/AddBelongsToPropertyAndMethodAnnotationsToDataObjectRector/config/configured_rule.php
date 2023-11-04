<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use SilverstripeRector\Silverstripe413\Rector\Class_\AddBelongsToPropertyAndMethodAnnotationsToDataObjectRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../../../../../../../config/config.php');
    $rectorConfig->rule(AddBelongsToPropertyAndMethodAnnotationsToDataObjectRector::class);
};
