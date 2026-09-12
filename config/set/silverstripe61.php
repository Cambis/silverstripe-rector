<?php

declare(strict_types=1);

use Cambis\SilverstripeRector\Silverstripe61\Rector\StaticCall\DataObjectDeleteByIdCachedRector;
use Cambis\SilverstripeRector\Silverstripe61\Rector\StaticCall\DataObjectGetByIdCachedRector;
use Cambis\SilverstripeRector\Silverstripe61\Rector\StaticCall\DataObjectGetOneCachedRector;
use Rector\Config\RectorConfig;

// https://docs.silverstripe.org/en/6/changelogs/6.1.0/
return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../config.php');

    // https://github.com/silverstripe/silverstripe-framework/issues/11767
    $rectorConfig->rules([
        DataObjectGetByIdCachedRector::class,
        DataObjectDeleteByIdCachedRector::class,
        DataObjectGetOneCachedRector::class,
    ]);
};
