<?php

declare(strict_types=1);

use PhpCsFixer\Fixer\Import\OrderedImportsFixer;
use PhpCsFixer\Fixer\Operator\NewWithBracesFixer;
use PhpCsFixer\Fixer\Operator\NotOperatorWithSuccessorSpaceFixer;
use SlevomatCodingStandard\Sniffs\Namespaces\ReferenceUsedNamesOnlySniff;
use Symplify\EasyCodingStandard\Config\ECSConfig;

return ECSConfig::configure()
    ->withPaths([
        __DIR__ . '/rector.php',
        __DIR__ . '/config',
        __DIR__ . '/rules',
        __DIR__ . '/src',
        __DIR__ . '/tests',
        __DIR__ . '/e2e',
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
    ->withConfiguredRule(
        OrderedImportsFixer::class,
        [
            'imports_order' => [
                'class',
                'function',
                'const',
            ],
        ]
    )
    ->withPreparedSets(common: true, psr12: true)
    ->withSkip([
        '*/Rector/*/Fixture/*',
        '*/Rector/*/Fixture*',
        '*/Source/*',
        '*/Source*',
        NotOperatorWithSuccessorSpaceFixer::class,
    ]);
