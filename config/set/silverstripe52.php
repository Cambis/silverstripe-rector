<?php

declare(strict_types=1);

use Cambis\SilverstripeRector\Silverstripe413\Rector\Class_\AddBelongsToPropertyAndMethodAnnotationsToDataObjectRector;
use Cambis\SilverstripeRector\Silverstripe413\Rector\Class_\AddDBFieldPropertyAnnotationsToDataObjectRector;
use Cambis\SilverstripeRector\Silverstripe413\Rector\Class_\AddExtensionMixinAnnotationsToExtensibleRector;
use Cambis\SilverstripeRector\Silverstripe413\Rector\Class_\AddHasOnePropertyAndMethodAnnotationsToDataObjectRector;
use Cambis\SilverstripeRector\Silverstripe413\Rector\Class_\CompleteDynamicInjectablePropertiesRector;
use Cambis\SilverstripeRector\Silverstripe52\Rector\Class_\AddBelongsManyManyMethodAnnotationsToDataObjectRector;
use Cambis\SilverstripeRector\Silverstripe52\Rector\Class_\AddExtendsAnnotationToExtensionRector;
use Cambis\SilverstripeRector\Silverstripe52\Rector\Class_\AddHasManyMethodAnnotationsToDataObjectRector;
use Cambis\SilverstripeRector\Silverstripe52\Rector\Class_\AddManyManyMethodAnnotationsToDataObjectRector;
use Cambis\SilverstripeRector\Silverstripe52\Rector\Class_\RemoveGetOwnerMethodAnnotationFromExtensionsRector;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../config.php');

    // Restart the sets from here to maintain the order of annotations
    $rectorConfig->rules([
        RemoveGetOwnerMethodAnnotationFromExtensionsRector::class,
        AddDBFieldPropertyAnnotationsToDataObjectRector::class,
        AddBelongsToPropertyAndMethodAnnotationsToDataObjectRector::class,
        AddHasOnePropertyAndMethodAnnotationsToDataObjectRector::class,
        AddHasManyMethodAnnotationsToDataObjectRector::class,
        AddBelongsManyManyMethodAnnotationsToDataObjectRector::class,
        AddManyManyMethodAnnotationsToDataObjectRector::class,
        AddExtendsAnnotationToExtensionRector::class,
        AddExtensionMixinAnnotationsToExtensibleRector::class,
        CompleteDynamicInjectablePropertiesRector::class,
    ]);
};
