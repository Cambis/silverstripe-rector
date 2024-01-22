<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use SilverstripeRector\Silverstripe413\Rector\Class_\AddBelongsToPropertyAndMethodAnnotationsToDataObjectRector;
use SilverstripeRector\Silverstripe413\Rector\Class_\AddDBFieldPropertyAnnotationsToDataObjectRector;
use SilverstripeRector\Silverstripe413\Rector\Class_\AddExtensionMixinAnnotationsToExtensibleRector;
use SilverstripeRector\Silverstripe413\Rector\Class_\AddHasOnePropertyAndMethodAnnotationsToDataObjectRector;
use SilverstripeRector\Silverstripe413\Rector\Class_\CompleteDynamicInjectablePropertiesRector;
use SilverstripeRector\Silverstripe52\Rector\Class_\AddBelongsManyManyMethodAnnotationsToDataObjectRector;
use SilverstripeRector\Silverstripe52\Rector\Class_\AddExtendsAnnotationToExtensionsRector;
use SilverstripeRector\Silverstripe52\Rector\Class_\AddHasManyMethodAnnotationsToDataObjectRector;
use SilverstripeRector\Silverstripe52\Rector\Class_\AddManyManyMethodAnnotationsToDataObjectRector;
use SilverstripeRector\Silverstripe52\Rector\Class_\DataListMethodAnnotationToGenericDataListMethodAnnotationRector;
use SilverstripeRector\Silverstripe52\Rector\Class_\RemoveGetOwnerMethodAnnotationFromExtensionsRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../config.php');

    // Restart the sets from here to maintain the order of annotations
    $rectorConfig->rules([
        RemoveGetOwnerMethodAnnotationFromExtensionsRector::class,
        DataListMethodAnnotationToGenericDataListMethodAnnotationRector::class,
        AddDBFieldPropertyAnnotationsToDataObjectRector::class,
        AddBelongsToPropertyAndMethodAnnotationsToDataObjectRector::class,
        AddHasOnePropertyAndMethodAnnotationsToDataObjectRector::class,
        AddHasManyMethodAnnotationsToDataObjectRector::class,
        AddBelongsManyManyMethodAnnotationsToDataObjectRector::class,
        AddManyManyMethodAnnotationsToDataObjectRector::class,
        AddExtendsAnnotationToExtensionsRector::class,
        AddExtensionMixinAnnotationsToExtensibleRector::class,
        CompleteDynamicInjectablePropertiesRector::class,
    ]);
};
