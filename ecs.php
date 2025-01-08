<?php

declare(strict_types=1);

use PhpCsFixer\Fixer\Operator\NewWithBracesFixer;
use PhpCsFixer\Fixer\Operator\NotOperatorWithSuccessorSpaceFixer;
use SlevomatCodingStandard\Sniffs\Namespaces\ReferenceUsedNamesOnlySniff;
use Symplify\EasyCodingStandard\Config\ECSConfig;
use Symplify\EasyCodingStandard\ValueObject\Set\SetList;

return ECSConfig::configure()
    ->withPaths([
        __DIR__ . '/rector.php',
        __DIR__ . '/config',
        __DIR__ . '/rules',
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ])
    ->withConfiguredRule(
        NewWithBracesFixer::class,
        [
            'anonymous_class' => false,
        ]
    )
    ->withConfiguredRule(
        ReferenceUsedNamesOnlySniff::class,
        [
            'allowFallbackGlobalFunctions' => false,
            'allowFallbackGlobalConstants' => false,
        ]
    )
    ->withSets([
        SetList::COMMON,
        SetList::PSR_12,
    ])
    ->withSkip([
        '*/Rector/*/Fixture/*',
        '*/Rector/*/Fixture*',
        '*/Source/*',
        '*/Source*',
        NotOperatorWithSuccessorSpaceFixer::class,
        ReferenceUsedNamesOnlySniff::class => [
            __DIR__ . '/bootstrap.php',
            __DIR__ . '/stubs/Page.php',
            __DIR__ . '/stubs/PageController.php',
        ],
    ]);
