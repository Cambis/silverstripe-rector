<?php

declare(strict_types=1);

use Cambis\SilverstripeRector\CodeQuality\Rector\StaticPropertyFetch\StaticPropertyFetchToConfigGetRector;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(StaticPropertyFetchToConfigGetRector::class);
};
