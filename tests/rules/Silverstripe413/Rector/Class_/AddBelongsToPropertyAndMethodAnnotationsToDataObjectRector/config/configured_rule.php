<?php

declare(strict_types=1);

use Cambis\SilverstripeRector\Silverstripe413\Rector\Class_\AddBelongsToPropertyAndMethodAnnotationsToDataObjectRector;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(AddBelongsToPropertyAndMethodAnnotationsToDataObjectRector::class);
};
