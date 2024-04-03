<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use SilverstripeRector\Set\ValueObject\SilverstripeSetList;
use SilverstripeRector\Tests\Silverstripe52\Rector\Class_\AddExtendsAnnotationToExtensionRector\IntersectionMultipleRector;

return RectorConfig::configure()
    ->withSets([SilverstripeSetList::WITH_SERVICES])
    ->withRules([IntersectionMultipleRector::class]);
