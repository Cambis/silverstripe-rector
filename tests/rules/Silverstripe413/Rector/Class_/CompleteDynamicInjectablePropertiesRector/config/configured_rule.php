<?php

declare(strict_types=1);

use Cambis\SilverstripeRector\Silverstripe413\Rector\Class_\CompleteDynamicInjectablePropertiesRector;
use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\SetList;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->importShortClasses();

    $rectorConfig->sets([
        SetList::TYPE_DECLARATION,
    ]);

    $rectorConfig->rules([
        CompleteDynamicInjectablePropertiesRector::class,
    ]);
};
