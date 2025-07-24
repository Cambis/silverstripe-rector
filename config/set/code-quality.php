<?php

declare(strict_types=1);

use Cambis\SilverstripeRector\CodeQuality\Rector\Assign\ConfigurationPropertyFetchToMethodCallRector;
use Cambis\SilverstripeRector\CodeQuality\Rector\New_\InjectableNewInstanceToCreateRector;
use Rector\Config\RectorConfig;
use Rector\Transform\Rector\Assign\PropertyFetchToMethodCallRector;
use Rector\Transform\ValueObject\PropertyFetchToMethodCall;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rules([
        InjectableNewInstanceToCreateRector::class,
        ConfigurationPropertyFetchToMethodCallRector::class,
    ]);

    $rectorConfig->ruleWithConfiguration(
        PropertyFetchToMethodCallRector::class,
        [
            new PropertyFetchToMethodCall(
                'SilverStripe\Core\Extension',
                'owner',
                'getOwner',
            ),
        ]
    );
};
