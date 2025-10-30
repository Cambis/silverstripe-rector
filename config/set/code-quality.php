<?php

declare(strict_types=1);

use Cambis\SilverstripeRector\CodeQuality\Rector\Assign\ConfigurationPropertyFetchToMethodCallRector;
use Cambis\SilverstripeRector\CodeQuality\Rector\New_\InjectableNewInstanceToCreateRector;
use Cambis\SilverstripeRector\CodeQuality\Rector\PropertyFetch\ExtensionOwnerToGetOwnerRector;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rules([
        InjectableNewInstanceToCreateRector::class,
        ConfigurationPropertyFetchToMethodCallRector::class,
        ExtensionOwnerToGetOwnerRector::class,
    ]);
};
