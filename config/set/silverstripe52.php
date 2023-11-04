<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use SilverstripeRector\Silverstripe413\Rector\Class_\AddBelongsManyManyMethodAnnotationsToDataObjectRector;
use SilverstripeRector\Silverstripe413\Rector\Class_\AddGetOwnerMethodAnnotationToExtensionsRector;
use SilverstripeRector\Silverstripe413\Rector\Class_\AddHasManyMethodAnnotationsToDataObjectRector;
use SilverstripeRector\Silverstripe413\Rector\Class_\AddManyManyMethodAnnotationsToDataObjectRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../config.php');
    $rectorConfig->skip([
        AddBelongsManyManyMethodAnnotationsToDataObjectRector::class,
        AddGetOwnerMethodAnnotationToExtensionsRector::class,
        AddHasManyMethodAnnotationsToDataObjectRector::class,
        AddManyManyMethodAnnotationsToDataObjectRector::class,
    ]);
};
