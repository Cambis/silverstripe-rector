<?php

declare(strict_types=1);

use Cambis\SilverstripeRector\Set\ValueObject\SilverstripeLevelSetList;
use Cambis\SilverstripeRector\Set\ValueObject\SilverstripeSetList;
use Cambis\SilverstripeRector\Silverstripe52\Rector\Class_\AddExtendsAnnotationToExtensionRector;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->sets([
        SilverstripeLevelSetList::UP_TO_SILVERSTRIPE_52,
        SilverstripeSetList::TYPE_DECLARATION_DOCBLOCKS,
    ]);

    $rectorConfig->rule(AddExtendsAnnotationToExtensionRector::class);
};
