<?php

declare(strict_types=1);

use Cambis\SilverstripeRector\Renaming\Rector\Class_\RenameExtensionHookMethodRector;
use Cambis\SilverstripeRector\Renaming\ValueObject\RenameExtensionHookMethod;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withConfiguredRule(
        RenameExtensionHookMethodRector::class,
        [new RenameExtensionHookMethod('Cambis\SilverstripeRector\Tests\Renaming\Rector\Class_\RenameExtensionHookMethodRector\Source\Foo', 'updateDoSomething', 'updateDoSomethingElse')]
    );
