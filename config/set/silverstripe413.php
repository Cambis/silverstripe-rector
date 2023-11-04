<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use SilverstripeRector\Silverstripe413\Rector\Class_\AddBelongsManyManyMethodAnnotationsToDataObjectRector;
use SilverstripeRector\Silverstripe413\Rector\Class_\AddBelongsToPropertyAndMethodAnnotationsToDataObjectRector;
use SilverstripeRector\Silverstripe413\Rector\Class_\AddDBFieldPropertyAnnotationsToDataObjectRector;
use SilverstripeRector\Silverstripe413\Rector\Class_\AddExtensionMixinAnnotationsToExtensibleRector;
use SilverstripeRector\Silverstripe413\Rector\Class_\AddGetOwnerMethodAnnotationToExtensionsRector;
use SilverstripeRector\Silverstripe413\Rector\Class_\AddHasManyMethodAnnotationsToDataObjectRector;
use SilverstripeRector\Silverstripe413\Rector\Class_\AddHasOnePropertyAndMethodAnnotationsToDataObjectRector;
use SilverstripeRector\Silverstripe413\Rector\Class_\AddManyManyMethodAnnotationsToDataObjectRector;
use SilverstripeRector\Silverstripe413\Rector\Class_\CompleteDynamicInjectablePropertiesRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../config.php');
    $rectorConfig->rules([
        AddDBFieldPropertyAnnotationsToDataObjectRector::class,
        AddBelongsToPropertyAndMethodAnnotationsToDataObjectRector::class,
        AddHasOnePropertyAndMethodAnnotationsToDataObjectRector::class,
        AddHasManyMethodAnnotationsToDataObjectRector::class,
        AddBelongsManyManyMethodAnnotationsToDataObjectRector::class,
        AddManyManyMethodAnnotationsToDataObjectRector::class,
        AddGetOwnerMethodAnnotationToExtensionsRector::class,
        AddExtensionMixinAnnotationsToExtensibleRector::class,
        CompleteDynamicInjectablePropertiesRector::class,
    ]);
};
