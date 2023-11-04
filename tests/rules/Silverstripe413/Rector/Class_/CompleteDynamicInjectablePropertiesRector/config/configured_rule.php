<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\SetList;
use SilverstripeRector\Silverstripe413\Rector\Class_\CompleteDynamicInjectablePropertiesRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../../../../../../../config/config.php');
    $rectorConfig->importShortClasses();

    $rectorConfig->sets([
        SetList::TYPE_DECLARATION,
    ]);

    $rectorConfig->rules([
        CompleteDynamicInjectablePropertiesRector::class,
    ]);
};
