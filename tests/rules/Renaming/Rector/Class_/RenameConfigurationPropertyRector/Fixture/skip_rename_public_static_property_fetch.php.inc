<?php

namespace Cambis\SilverstripeRector\Tests\Renaming\Rector\Class_\RenameConfigurationPropertyRector\Fixture;

class SkipRenamePublicStaticPropertyFetch extends \SilverStripe\ORM\DataObject implements \SilverStripe\Dev\TestOnly
{
    public static string $description = '';

    public function getNiceDescription(): string
    {
        return self::$description;
    }
}
