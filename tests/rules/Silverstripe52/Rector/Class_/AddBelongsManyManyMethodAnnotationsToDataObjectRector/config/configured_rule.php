<?php

declare(strict_types=1);

use Cambis\SilverstripeRector\Silverstripe52\Rector\Class_\AddBelongsManyManyMethodAnnotationsToDataObjectRector;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(AddBelongsManyManyMethodAnnotationsToDataObjectRector::class);
};
