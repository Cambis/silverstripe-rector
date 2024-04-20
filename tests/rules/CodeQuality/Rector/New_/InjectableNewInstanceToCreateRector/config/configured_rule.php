<?php

declare(strict_types=1);

use Cambis\SilverstripeRector\CodeQuality\Rector\New_\InjectableNewInstanceToCreateRector;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(InjectableNewInstanceToCreateRector::class);
};
