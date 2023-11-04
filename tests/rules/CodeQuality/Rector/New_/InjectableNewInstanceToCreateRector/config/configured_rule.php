<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use SilverstripeRector\CodeQuality\Rector\New_\InjectableNewInstanceToCreateRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(InjectableNewInstanceToCreateRector::class);
};
