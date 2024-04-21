<?php

declare(strict_types=1);

use Cambis\SilverstripeRector\Set\ValueObject\SilverstripeSetList;
use Cambis\SilverstripeRector\Tests\Silverstripe52\Rector\Class_\AddExtendsAnnotationToExtensionRector\IntersectionMultipleRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withSets([
        SilverstripeSetList::WITH_SILVERSTRIPE_API,
        SilverstripeSetList::WITH_RECTOR_SERVICES,
    ])
    ->withRules([IntersectionMultipleRector::class]);
