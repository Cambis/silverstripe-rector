<?php

namespace Cambis\SilverstripeRector\Tests\Renaming\Rector\Class_\RenameConfigurationPropertyRector\Fixture;

class RenameConfigurationProperty extends \SilverStripe\ORM\DataObject implements \SilverStripe\Dev\TestOnly
{
    private static string $description = '';

    public function getTitle(): string
    {
        return $this->Description ?? '';
    }

    public function getNiceDescription(): string
    {
        return $this->config()->get('description');
    }
}

?>
-----
<?php

namespace Cambis\SilverstripeRector\Tests\Renaming\Rector\Class_\RenameConfigurationPropertyRector\Fixture;

class RenameConfigurationProperty extends \SilverStripe\ORM\DataObject implements \SilverStripe\Dev\TestOnly
{
    private static string $class_description = '';

    public function getTitle(): string
    {
        return $this->Description ?? '';
    }

    public function getNiceDescription(): string
    {
        return $this->config()->get('class_description');
    }
}

?>
