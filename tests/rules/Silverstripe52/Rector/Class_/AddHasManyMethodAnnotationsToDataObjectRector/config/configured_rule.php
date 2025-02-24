<?php

declare(strict_types=1);

use Cambis\SilverstripeRector\Silverstripe52\Rector\Class_\AddHasManyMethodAnnotationsToDataObjectRector;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(AddHasManyMethodAnnotationsToDataObjectRector::class);
};
