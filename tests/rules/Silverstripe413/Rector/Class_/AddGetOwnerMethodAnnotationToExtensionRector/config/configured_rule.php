<?php

declare(strict_types=1);

use Cambis\SilverstripeRector\Silverstripe413\Rector\Class_\AddGetOwnerMethodAnnotationToExtensionRector;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(AddGetOwnerMethodAnnotationToExtensionRector::class);
};
