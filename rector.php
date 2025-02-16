<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php55\Rector\String_\StringClassNameToClassConstantRector;
use Rector\Php74\Rector\Closure\ClosureToArrowFunctionRector;
use Rector\Php83\Rector\ClassConst\AddTypeToConstRector;
use Rector\PHPUnit\PHPUnit60\Rector\ClassMethod\AddDoesNotPerformAssertionToNonAssertingTestRector;
use Rector\TypeDeclaration\Rector\StmtsAwareInterface\DeclareStrictTypesRector;

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
        privatization: true,
        phpunit: true,
        phpunitCodeQuality: true
    )
    ->withRules([
        DeclareStrictTypesRector::class,
    ])
    ->withSkip([
        '*/Rector/*/Fixture/*',
        '*/Source/*',
        ClosureToArrowFunctionRector::class,
        // This may cause a downgrade to fail
        AddTypeToConstRector::class,
        // Some rectors use FQN names
        StringClassNameToClassConstantRector::class,
        // Skip false postive in AbstractRector::test()
        AddDoesNotPerformAssertionToNonAssertingTestRector::class => [
            __DIR__ . '/tests/rules/**/Rector/**/*RectorTest.php',
            __DIR__ . '/tests/src/Set/**/Silverstripe**Test.php',
        ],
    ]);
