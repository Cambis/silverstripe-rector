<?php

namespace Cambis\SilverstripeRector\Tests\CodeQuality\Rector\PropertyFetch\ExtensionOnwerToGetOwnerRector\Fixture;

class FooExtension extends \SilverStripe\Core\Extension implements \SilverStripe\Dev\TestOnly
{
    public function doSomething(): void
    {
        $this->owner->doSomething();
    }
}

?>
-----
<?php

namespace Cambis\SilverstripeRector\Tests\CodeQuality\Rector\PropertyFetch\ExtensionOnwerToGetOwnerRector\Fixture;

class FooExtension extends \SilverStripe\Core\Extension implements \SilverStripe\Dev\TestOnly
{
    public function doSomething(): void
    {
        $this->getOwner()->doSomething();
    }
}

?>
