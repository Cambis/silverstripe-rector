<?php

namespace Cambis\SilverstripeRector\Tests\Renaming\Rector\Class_\RenameExtensionHookMethodRector\Source;

use SilverStripe\Dev\TestOnly;
use SilverStripe\ORM\DataObject;

final class Foo extends DataObject implements TestOnly
{
    private static array $extensions = [
        'Cambis\SilverstripeRector\Tests\Renaming\Rector\Class_\RenameExtensionHookMethodRector\Fixture\RenameFromConfig',
    ];
}
