<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use SilverstripeRector\CodeQuality\Rector\StaticCall\DataObjectGetByIDCachedToUncachedRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(DataObjectGetByIDCachedToUncachedRector::class);
};
