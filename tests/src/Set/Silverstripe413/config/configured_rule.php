<?php

declare(strict_types=1);

use Cambis\SilverstripeRector\Set\ValueObject\SilverstripeLevelSetList;
use Cambis\SilverstripeRector\Set\ValueObject\SilverstripeSetList;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->sets([
        SilverstripeLevelSetList::UP_TO_SILVERSTRIPE_413,
        SilverstripeSetList::TYPE_DECLARATION_DOCBLOCKS,
    ]);

    // $rectorConfig->rules([
    //     AddDBFieldPropertyAnnotationsToDataObjectRector::class,
    //     AddBelongsToPropertyAndMethodAnnotationsToDataObjectRector::class,
    //     AddHasOnePropertyAndMethodAnnotationsToDataObjectRector::class,
    //     AddHasManyMethodAnnotationsToDataObjectRector::class,
    //     AddBelongsManyManyMethodAnnotationsToDataObjectRector::class,
    //     AddManyManyMethodAnnotationsToDataObjectRector::class,
    //     AddGetOwnerMethodAnnotationToExtensionRector::class,
    //     AddExtensionMixinAnnotationsToExtensibleRector::class,
    //     CompleteDynamicInjectablePropertiesRector::class,
    // ]);
};
