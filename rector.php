<?php

declare(strict_types=1);

use Cambis\SilverstripeRector\Set\ValueObject\SilverstripeSetList;
use Rector\Config\RectorConfig;
use Rector\Php55\Rector\String_\StringClassNameToClassConstantRector;
use Rector\Php74\Rector\Closure\ClosureToArrowFunctionRector;
use Rector\Php83\Rector\ClassConst\AddTypeToConstRector;

return RectorConfig::configure()
    ->withImportNames()
    ->withPaths([
        __DIR__ . '/rules',
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ])
    ->withPhpSets()
    ->withPreparedSets(
        codeQuality: true,
        codingStyle: true,
        deadCode: true,
        earlyReturn: true,
        privatization: true
    )
    ->withSets([
        SilverstripeSetList::SILVERSTRIPE_53,
    ])
    ->withSkip([
        '*/Rector/*/Fixture/*',
        '*/Source/*',
        ClosureToArrowFunctionRector::class,
        // This may cause a downgrade to fail
        AddTypeToConstRector::class,
        // Some rectors use FQN names
        StringClassNameToClassConstantRector::class,
    ]);
