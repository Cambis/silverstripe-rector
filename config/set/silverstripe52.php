<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use SilverstripeRector\Silverstripe413\Rector\Class_\AddBelongsManyManyMethodAnnotationsToDataObjectRector as LegacyAddBelongsManyManyMethodAnnotationsToDataObjectRector;
use SilverstripeRector\Silverstripe413\Rector\Class_\AddGetOwnerMethodAnnotationToExtensionsRector;
use SilverstripeRector\Silverstripe413\Rector\Class_\AddHasManyMethodAnnotationsToDataObjectRector as LegacyAddHasManyMethodAnnotationsToDataObjectRector;
use SilverstripeRector\Silverstripe413\Rector\Class_\AddManyManyMethodAnnotationsToDataObjectRector as LegacyAddManyManyMethodAnnotationsToDataObjectRector;
use SilverstripeRector\Silverstripe52\Rector\Class_\AddHasManyMethodAnnotationsToDataObjectRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../config.php');
    $rectorConfig->rules([
        AddHasManyMethodAnnotationsToDataObjectRector::class,
    ]);
    $rectorConfig->skip([
        LegacyAddBelongsManyManyMethodAnnotationsToDataObjectRector::class,
        AddGetOwnerMethodAnnotationToExtensionsRector::class,
        LegacyAddHasManyMethodAnnotationsToDataObjectRector::class,
        LegacyAddManyManyMethodAnnotationsToDataObjectRector::class,
    ]);
};
