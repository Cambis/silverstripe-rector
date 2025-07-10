<?php

declare(strict_types=1);

use Cambis\SilverstripeRector\CodeQuality\Rector\New_\InjectableNewInstanceToCreateRector;
use Cambis\SilverstripeRector\CodeQuality\Rector\StaticPropertyFetch\StaticPropertyFetchToConfigGetRector;
use Rector\Config\RectorConfig;
use Rector\Transform\Rector\Assign\PropertyFetchToMethodCallRector;
use Rector\Transform\ValueObject\PropertyFetchToMethodCall;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rules([
        InjectableNewInstanceToCreateRector::class,
        StaticPropertyFetchToConfigGetRector::class,
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
