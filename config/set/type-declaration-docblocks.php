<?php

declare(strict_types=1);

use Cambis\SilverstripeRector\Silverstripe413\Rector\Class_\AddBelongsManyManyMethodAnnotationsToDataObjectRector;
use Cambis\SilverstripeRector\Silverstripe413\Rector\Class_\AddBelongsToPropertyAndMethodAnnotationsToDataObjectRector;
use Cambis\SilverstripeRector\Silverstripe413\Rector\Class_\AddDBFieldPropertyAnnotationsToDataObjectRector;
use Cambis\SilverstripeRector\Silverstripe413\Rector\Class_\AddExtensionMixinAnnotationsToExtensibleRector;
use Cambis\SilverstripeRector\Silverstripe413\Rector\Class_\AddGetOwnerMethodAnnotationToExtensionRector;
use Cambis\SilverstripeRector\Silverstripe413\Rector\Class_\AddHasManyMethodAnnotationsToDataObjectRector;
use Cambis\SilverstripeRector\Silverstripe413\Rector\Class_\AddHasOnePropertyAndMethodAnnotationsToDataObjectRector;
use Cambis\SilverstripeRector\Silverstripe413\Rector\Class_\AddManyManyMethodAnnotationsToDataObjectRector;
use Cambis\SilverstripeRector\Silverstripe413\Rector\Class_\CompleteDynamicInjectablePropertiesRector;
use Cambis\SilverstripeRector\Silverstripe52\Rector\Class_\AddBelongsManyManyMethodAnnotationsToDataObjectRector as GenericsAddBelongsManyManyMethodAnnotationsToDataObjectRector;
use Cambis\SilverstripeRector\Silverstripe52\Rector\Class_\AddExtendsAnnotationToContentControllerRector;
use Cambis\SilverstripeRector\Silverstripe52\Rector\Class_\AddExtendsAnnotationToExtensionRector;
use Cambis\SilverstripeRector\Silverstripe52\Rector\Class_\AddHasManyMethodAnnotationsToDataObjectRector as GenericsAddHasManyMethodAnnotationsToDataObjectRector;
use Cambis\SilverstripeRector\Silverstripe52\Rector\Class_\AddManyManyMethodAnnotationsToDataObjectRector as GenericsAddManyManyMethodAnnotationsToDataObjectRector;
use Cambis\SilverstripeRector\Silverstripe52\Rector\Class_\RemoveGetOwnerMethodAnnotationFromExtensionsRector;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../config.php');

    $rectorConfig->rule(AddDBFieldPropertyAnnotationsToDataObjectRector::class);
    $rectorConfig->rule(AddBelongsToPropertyAndMethodAnnotationsToDataObjectRector::class);
    $rectorConfig->rule(AddHasOnePropertyAndMethodAnnotationsToDataObjectRector::class);

    $rectorConfig->rule(AddHasManyMethodAnnotationsToDataObjectRector::class);
    $rectorConfig->rule(GenericsAddHasManyMethodAnnotationsToDataObjectRector::class);

    $rectorConfig->rule(AddBelongsManyManyMethodAnnotationsToDataObjectRector::class);
    $rectorConfig->rule(GenericsAddBelongsManyManyMethodAnnotationsToDataObjectRector::class);

    $rectorConfig->rule(AddManyManyMethodAnnotationsToDataObjectRector::class);
    $rectorConfig->rule(GenericsAddManyManyMethodAnnotationsToDataObjectRector::class);

    $rectorConfig->rule(AddGetOwnerMethodAnnotationToExtensionRector::class);
    $rectorConfig->rule(RemoveGetOwnerMethodAnnotationFromExtensionsRector::class);

    $rectorConfig->ruleWithConfigurationComposerVersionBound(
        AddExtendsAnnotationToExtensionRector::class,
        [
            'allowSubclasses' => true,
        ],
        'silverstripe/framework',
        '>=5.2 <5.3'
    );

    $rectorConfig->ruleWithConfigurationComposerVersionBound(
        AddExtendsAnnotationToExtensionRector::class,
        [
            'allowSubclasses' => false,
        ],
        'silverstripe/framework',
        '>=5.3'
    );

    $rectorConfig->rule(AddExtensionMixinAnnotationsToExtensibleRector::class);
    $rectorConfig->rule(CompleteDynamicInjectablePropertiesRector::class);

    $rectorConfig->rule(AddExtendsAnnotationToContentControllerRector::class);
};
