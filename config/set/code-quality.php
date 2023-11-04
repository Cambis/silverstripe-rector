<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Transform\Rector\Assign\PropertyFetchToMethodCallRector;
use Rector\Transform\ValueObject\PropertyFetchToMethodCall;
use SilverStripe\Core\Extension;
use SilverstripeRector\CodeQuality\Rector\New_\InjectableNewInstanceToCreateRector;
use SilverstripeRector\CodeQuality\Rector\StaticCall\DataObjectGetByIDCachedToUncachedRector;
use SilverstripeRector\CodeQuality\Rector\StaticPropertyFetch\StaticPropertyFetchToConfigGetRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rules([
        DataObjectGetByIDCachedToUncachedRector::class,
        InjectableNewInstanceToCreateRector::class,
        StaticPropertyFetchToConfigGetRector::class,
    ]);

    $rectorConfig->ruleWithConfiguration(
        PropertyFetchToMethodCallRector::class,
        [
            new PropertyFetchToMethodCall(
                Extension::class,
                'owner',
                'getOwner',
            ),
        ]
    );
};
