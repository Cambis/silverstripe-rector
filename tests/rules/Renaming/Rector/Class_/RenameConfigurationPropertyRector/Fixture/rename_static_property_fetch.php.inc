<?php

namespace Cambis\SilverstripeRector\Tests\Renaming\Rector\Class_\RenameConfigurationPropertyRector\Fixture;

class RenameStaticPropertyFetch extends \SilverStripe\ORM\DataObject implements \SilverStripe\Dev\TestOnly
{
    private static string $description = '';

    public function getNiceDescription(): string
    {
        return self::$description;
    }
}

?>
-----
<?php

namespace Cambis\SilverstripeRector\Tests\Renaming\Rector\Class_\RenameConfigurationPropertyRector\Fixture;

class RenameStaticPropertyFetch extends \SilverStripe\ORM\DataObject implements \SilverStripe\Dev\TestOnly
{
    private static string $class_description = '';

    public function getNiceDescription(): string
    {
        return self::$class_description;
    }
}

?>
