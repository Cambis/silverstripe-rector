<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\If_\SimplifyIfReturnBoolRector;
use Rector\Config\RectorConfig;
use Rector\EarlyReturn\Rector\If_\ChangeAndIfToEarlyReturnRector;
use Rector\Php74\Rector\Closure\ClosureToArrowFunctionRector;
use Rector\Php83\Rector\ClassConst\AddTypeToConstRector;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;

return RectorConfig::configure()
    ->withImportNames()
    ->withPaths([
        __DIR__ . '/rules',
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ])->withSets([
        LevelSetList::UP_TO_PHP_83,
        SetList::CODE_QUALITY,
        SetList::CODING_STYLE,
        SetList::DEAD_CODE,
        SetList::EARLY_RETURN,
        SetList::PRIVATIZATION,
    ])->withSkip([
        '*/Fixture/*',
        '*/Source/*',
        ClosureToArrowFunctionRector::class,
        // This may cause a downgrade to fail
        AddTypeToConstRector::class,
        // This class has some complicated if statements
        ChangeAndIfToEarlyReturnRector::class => [
            __DIR__ . '/src/PhpDocManipulator/AnnotationUpdater.php',
        ],
        SimplifyIfReturnBoolRector::class => [
            __DIR__ . '/src/PhpDocManipulator/AnnotationUpdater.php',
        ],
    ]);
